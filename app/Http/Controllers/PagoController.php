<?php

namespace App\Http\Controllers;

use App\Models\CarritoItem;
use App\Models\Notificacion;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use App\Models\Tarjeta;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PagoController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── 1. MOSTRAR formulario de pago ────────────────────────────────────────

    /**
     * GET /pago/iniciar
     * Crea el pedido en estado "pendiente" con timer de 3 min y muestra la UI.
     */
    public function iniciar()
    {
        $user  = Auth::user();
        $items = CarritoItem::with(['producto.imagenes', 'producto.user'])
            ->where('user_id', $user->id)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('carrito.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        // Verificar que todos los productos estén activos
        $inactivos = $items->filter(fn($i) => $i->producto->estado !== 'activo');
        if ($inactivos->isNotEmpty()) {
            return redirect()->route('carrito.index')
                ->with('error', 'Algunos productos de tu carrito ya no están disponibles.');
        }

        // Cancelar pedido pendiente anterior si lo hay (ya expiró el timer)
        $pedidoAnterior = Pedido::where('user_id', $user->id)
            ->where('estado', 'pendiente')
            ->first();

        if ($pedidoAnterior) {
            if ($pedidoAnterior->timerExpirado()) {
                $pedidoAnterior->update(['estado' => 'cancelado']);
                Notificacion::crearCancelacion($user, $pedidoAnterior);
            } else {
                // Aún tiene tiempo — redirigir al pago existente
                $tarjetas = Tarjeta::where('user_id', $user->id)
                    ->where('predeterminada', false)
                    ->orWhere(fn($q) => $q->where('user_id', $user->id)->where('predeterminada', true))
                    ->orderByDesc('predeterminada')
                    ->get();

                $subtotal = $items->sum('total_calculado');
                return view('pago.index', compact('items', 'subtotal', 'tarjetas'))
                    ->with('pedido', $pedidoAnterior);
            }
        }

        // Crear nuevo pedido pendiente
        DB::beginTransaction();
        try {
            $subtotal = $items->sum('total_calculado');

            $pedido = Pedido::create([
                'folio'       => Pedido::generarFolio(),
                'user_id'     => $user->id,
                'subtotal'    => $subtotal,
                'total'       => $subtotal, // sin impuestos en mock
                'estado'      => 'pendiente',
                'pago_limite' => now()->addMinutes(3),
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('carrito.index')
                ->with('error', 'No pudimos iniciar el proceso de pago. Intenta de nuevo.');
        }

        $tarjetas = Tarjeta::where('user_id', $user->id)
            ->orderByDesc('predeterminada')
            ->get();

        return view('pago.index', compact('items', 'subtotal', 'tarjetas', 'pedido'));
    }

    // ── 2. PROCESAR pago ─────────────────────────────────────────────────────

    /**
     * POST /pago/procesar
     */
    public function procesar(Request $request)
    {
        $user   = Auth::user();
        $pedido = Pedido::where('user_id', $user->id)
            ->where('estado', 'pendiente')
            ->firstOrFail();

        // Verificar timer
        if ($pedido->timerExpirado()) {
            $pedido->update(['estado' => 'cancelado']);
            Notificacion::crearCancelacion($user, $pedido);
            return redirect()->route('carrito.index')
                ->with('error', 'El tiempo para completar tu pago expiró. Por favor intenta de nuevo.');
        }

        // ── Determinar método de pago ────────────────────────────────────────
        $usarTarjetaGuardada = $request->filled('tarjeta_guardada_id');

        if ($usarTarjetaGuardada) {
            $tarjeta = Tarjeta::where('id', $request->tarjeta_guardada_id)
                ->where('user_id', $user->id)
                ->firstOrFail();
        } else {
            // Validar tarjeta nueva
            $request->validate([
                'numero' => ['required', 'regex:/^[\d\s]{13,23}$/'],
                'titular'   => ['required', 'string', 'max:100'],
                'exp_mes'   => ['required', 'integer', 'between:1,12'],
                'exp_anio'  => ['required', 'integer', 'min:' . date('Y')],
                'cvv'       => ['required', 'digits_between:3,4'],
            ], [
                'numero.required'   => 'El número de tarjeta es obligatorio.',
                'numero.digits_between' => 'El número de tarjeta debe tener entre 13 y 19 dígitos.',
                'titular.required'  => 'El nombre del titular es obligatorio.',
                'exp_mes.required'  => 'El mes de vencimiento es obligatorio.',
                'exp_anio.required' => 'El año de vencimiento es obligatorio.',
                'cvv.required'      => 'El CVV es obligatorio.',
                'cvv.digits_between'=> 'El CVV debe tener 3 o 4 dígitos.',
            ]);

            $numeroLimpio   = preg_replace('/\D/', '', $request->numero);
            $ultimosCuatro  = substr($numeroLimpio, -4);
            $tipoDetectado  = Tarjeta::detectarTipo($numeroLimpio);

            // Guardar tarjeta si el usuario lo pidió
            $tarjeta = null;
            if ($request->boolean('guardar_tarjeta')) {
                // Si se marca predeterminada, desmarcar las anteriores
                if ($request->boolean('predeterminada')) {
                    Tarjeta::where('user_id', $user->id)->update(['predeterminada' => false]);
                }

                $tarjeta = Tarjeta::create([
                    'user_id'         => $user->id,
                    'ultimos_cuatro'  => $ultimosCuatro,
                    'tipo'            => $tipoDetectado,
                    'titular'         => strtoupper(trim($request->titular)),
                    'exp_mes'         => $request->exp_mes,
                    'exp_anio'        => $request->exp_anio,
                    'token_simulado'  => Tarjeta::generarToken(),
                    'predeterminada'  => $request->boolean('predeterminada'),
                ]);
            }
        }

        // ── Procesar el pedido ───────────────────────────────────────────────
        $items = CarritoItem::with(['producto.user'])
            ->where('user_id', $user->id)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('carrito.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        DB::beginTransaction();
        try {
            // Actualizar pedido con datos de pago
            $tarjetaId     = $tarjeta?->id;
            $ultCuatro = $tarjeta?->ultimos_cuatro 
    ?? substr(preg_replace('/\D/', '', $request->numero ?? ''), -4);
            $tipoTarjeta   = $tarjeta?->tipo ?? ($usarTarjetaGuardada ? null : Tarjeta::detectarTipo(preg_replace('/\D/', '', $request->numero ?? '')));

            $pedido->update([
                'tarjeta_id'             => $tarjetaId,
                'tarjeta_ultimos_cuatro' => $ultCuatro,
                'tarjeta_tipo'           => $tipoTarjeta,
                'estado'                 => 'pagado',
                'pagado_at'              => now(),
            ]);

            // Crear pedido_items y marcar productos como vendidos/ocupados
            $vendedoresNotificados = [];

            foreach ($items as $item) {
                $producto = $item->producto;

                $pedidoItem = PedidoItem::create([
                    'pedido_id'       => $pedido->id,
                    'producto_id'     => $producto->id,
                    'titulo'          => $producto->titulo,
                    'tipo_accion'     => $item->tipo_accion,
                    'precio_unitario' => $item->precio_unitario,
                    'cantidad'        => $item->cantidad,
                    'fecha_inicio'    => $item->fecha_inicio,
                    'fecha_fin'       => $item->fecha_fin,
                    'total_item'      => $item->total_calculado,
                    'vendedor_id'     => $producto->user_id,
                ]);

                // Marcar producto como vendido si es venta (rentas siguen activas)
                if ($item->tipo_accion === 'comprar') {
                    $producto->update(['estado' => 'vendido']);
                }

                // Notificar al vendedor (una vez por vendedor por pedido)
                $vendedorId = $producto->user_id;
                if (!in_array($vendedorId, $vendedoresNotificados)) {
                    Notificacion::crearParaVendedor($producto->user, $pedidoItem, $pedido);
                    $vendedoresNotificados[] = $vendedorId;
                }
            }

            // Notificar al comprador
            Notificacion::crearParaComprador($user, $pedido);

            // Vaciar carrito
            CarritoItem::where('user_id', $user->id)->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error al procesar tu pago. Intenta de nuevo.');
        }

        return redirect()->route('pago.confirmacion', $pedido)
            ->with('success', "¡Pago exitoso! Pedido #{$pedido->folio} confirmado.");
    }

    // ── 3. CONFIRMACIÓN ──────────────────────────────────────────────────────

    /**
     * GET /pago/{pedido}/confirmacion
     */
    public function confirmacion(Pedido $pedido)
    {
        // Solo el comprador puede ver su confirmación
        if ($pedido->user_id !== Auth::id()) abort(403);

        $pedido->load(['items.producto.imagenes', 'tarjeta']);

        return view('pago.confirmacion', compact('pedido'));
    }

    // ── 4. VERIFICAR timer (AJAX) ────────────────────────────────────────────

    /**
     * GET /pago/timer-status  (llamada AJAX cada 5 segundos desde el frontend)
     */
    public function timerStatus()
    {
        $pedido = Pedido::where('user_id', Auth::id())
            ->where('estado', 'pendiente')
            ->first();

        if (!$pedido) {
            return response()->json(['expirado' => true, 'segundos' => 0]);
        }

        $expirado = $pedido->timerExpirado();

        if ($expirado) {
            $pedido->update(['estado' => 'cancelado']);
            Notificacion::crearCancelacion(Auth::user(), $pedido);
        }

        return response()->json([
            'expirado' => $expirado,
            'segundos' => $pedido->segundosRestantes(),
        ]);
    }

    // ── 5. ELIMINAR tarjeta guardada ─────────────────────────────────────────

    /**
     * DELETE /pago/tarjetas/{tarjeta}
     */
    public function eliminarTarjeta(Tarjeta $tarjeta)
    {
        if ($tarjeta->user_id !== Auth::id()) abort(403);
        $tarjeta->delete();

        return back()->with('success', 'Tarjeta eliminada correctamente.');
    }
}