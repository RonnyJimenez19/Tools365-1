<?php

namespace App\Http\Controllers;

use App\Models\CarritoItem;
use App\Models\Notificacion;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use App\Models\Tarjeta;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PagoController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── 1. MOSTRAR formulario de pago ────────────────────────────────────────

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

        $inactivos = $items->filter(fn($i) => $i->producto->estado !== 'activo');
        if ($inactivos->isNotEmpty()) {
            return redirect()->route('carrito.index')
                ->with('error', 'Algunos productos de tu carrito ya no están disponibles.');
        }

        $pedidoAnterior = Pedido::where('user_id', $user->id)
            ->where('estado', 'pendiente')
            ->first();

        if ($pedidoAnterior) {
            if ($pedidoAnterior->timerExpirado()) {
                $pedidoAnterior->update(['estado' => 'cancelado']);
                Notificacion::crearCancelacion($user, $pedidoAnterior);
            } else {
                $tarjetas = Tarjeta::where('user_id', $user->id)
                    ->orderByDesc('predeterminada')
                    ->get();
                $subtotal = $items->sum('total_calculado');
                return view('pago.index', compact('items', 'subtotal', 'tarjetas'))
                    ->with('pedido', $pedidoAnterior);
            }
        }

        DB::beginTransaction();
        try {
            $subtotal = $items->sum('total_calculado');

            $pedido = Pedido::create([
                'folio'       => Pedido::generarFolio(),
                'user_id'     => $user->id,
                'subtotal'    => $subtotal,
                'total'       => $subtotal,
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

    public function procesar(Request $request)
    {
        $user   = Auth::user();
        $pedido = Pedido::where('user_id', $user->id)
            ->where('estado', 'pendiente')
            ->firstOrFail();

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
            $request->validate([
                'numero'   => ['required', 'regex:/^[\d\s]{13,23}$/'],
                'titular'  => ['required', 'string', 'max:100'],
                'exp_mes'  => ['required', 'integer', 'between:1,12'],
                'exp_anio' => ['required', 'integer', 'min:' . date('Y')],
                'cvv'      => ['required', 'digits_between:3,4'],
            ], [
                'numero.required'  => 'El número de tarjeta es obligatorio.',
                'titular.required' => 'El nombre del titular es obligatorio.',
                'exp_mes.required' => 'El mes de vencimiento es obligatorio.',
                'exp_anio.required'=> 'El año de vencimiento es obligatorio.',
                'cvv.required'     => 'El CVV es obligatorio.',
                'cvv.digits_between' => 'El CVV debe tener 3 o 4 dígitos.',
            ]);

            $numeroLimpio  = preg_replace('/\D/', '', $request->numero);
            $tipoDetectado = Tarjeta::detectarTipo($numeroLimpio);
            $tarjeta       = null;

            if ($request->boolean('guardar_tarjeta')) {
                if ($request->boolean('predeterminada')) {
                    Tarjeta::where('user_id', $user->id)->update(['predeterminada' => false]);
                }
                $tarjeta = Tarjeta::create([
                    'user_id'        => $user->id,
                    'ultimos_cuatro' => substr($numeroLimpio, -4),
                    'tipo'           => $tipoDetectado,
                    'titular'        => strtoupper(trim($request->titular)),
                    'exp_mes'        => $request->exp_mes,
                    'exp_anio'       => $request->exp_anio,
                    'token_simulado' => Tarjeta::generarToken(),
                    'predeterminada' => $request->boolean('predeterminada'),
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
            $tarjetaId   = $tarjeta?->id;
            $ultCuatro   = $tarjeta?->ultimos_cuatro
                ?? substr(preg_replace('/\D/', '', $request->numero ?? ''), -4);
            $tipoTarjeta = $tarjeta?->tipo
                ?? ($usarTarjetaGuardada ? null : Tarjeta::detectarTipo(preg_replace('/\D/', '', $request->numero ?? '')));

            $pedido->update([
                'tarjeta_id'             => $tarjetaId,
                'tarjeta_ultimos_cuatro' => $ultCuatro,
                'tarjeta_tipo'           => $tipoTarjeta,
                'estado'                 => 'pagado',
                'pagado_at'              => now(),
            ]);

            $comisionTotalPedido   = 0;
            $vendedoresNotificados = [];

            foreach ($items as $item) {
                $producto     = $item->producto;
                $vendedor     = $producto->user;
                $planVendedor = $vendedor->plan ?? 'free';

                // ── Calcular comisión según plan del VENDEDOR ────────────────
                $calc = PlanService::calcular($item->total_calculado, $planVendedor);

                $pedidoItem = PedidoItem::create([
                    'pedido_id'           => $pedido->id,
                    'producto_id'         => $producto->id,
                    'titulo'              => $producto->titulo,
                    'tipo_accion'         => $item->tipo_accion,
                    'precio_unitario'     => $item->precio_unitario,
                    'cantidad'            => $item->cantidad,
                    'fecha_inicio'        => $item->fecha_inicio,
                    'fecha_fin'           => $item->fecha_fin,
                    'total_item'          => $item->total_calculado,
                    'comision_pct'        => $calc['comision_pct'],
                    'comision_plataforma' => $calc['comision_monto'],
                    'neto_vendedor'       => $calc['neto_vendedor'],
                    'vendedor_id'         => $producto->user_id,
                ]);

                $comisionTotalPedido += $calc['comision_monto'];

                if ($item->tipo_accion === 'comprar') {
                    $producto->update(['estado' => 'vendido']);
                } elseif ($item->tipo_accion === 'rentar') {
                    $producto->update(['estado' => 'pausado']);
                }

                $vendedorId = $producto->user_id;
                if (!in_array($vendedorId, $vendedoresNotificados)) {
                    Notificacion::crearParaVendedor($vendedor, $pedidoItem, $pedido);
                    $vendedoresNotificados[] = $vendedorId;
                }
            }

            // Guardar comisión total del pedido
            $pedido->update(['comision_total' => round($comisionTotalPedido, 2)]);

            Notificacion::crearParaComprador($user, $pedido);
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

    public function confirmacion(Pedido $pedido)
    {
        if ($pedido->user_id !== Auth::id()) abort(403);
        $pedido->load(['items.producto.imagenes', 'tarjeta']);
        return view('pago.confirmacion', compact('pedido'));
    }

    // ── 4. TIMER (AJAX) ──────────────────────────────────────────────────────

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

    public function eliminarTarjeta(Tarjeta $tarjeta)
    {
        if ($tarjeta->user_id !== Auth::id()) abort(403);
        $tarjeta->delete();
        return back()->with('success', 'Tarjeta eliminada correctamente.');
    }
}