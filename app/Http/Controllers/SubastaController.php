<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\SubastaPuja;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubastaController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ──────────────────────────────────────────────────────
    // GET /subastas
    // ──────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user = Auth::user();

        $publicadas = Producto::with(['imagenes', 'pujas' => fn($q) => $q->latest()->limit(1)])
            ->where('user_id', $user->id)
            ->where('tipo', 'subasta')
            ->withCount('pujas')
            ->latest()
            ->get()
            ->map(function ($p) {
                $p->puja_actual = $p->pujas()->max('monto') ?? $p->precio;
                $p->puja_top    = $p->pujas()->with('user')->orderByDesc('monto')->first();
                $p->activa      = $p->estado === 'activo'
                               && $p->timer_fin
                               && Carbon::parse($p->timer_fin)->isFuture();
                return $p;
            });

        $participando = Producto::with(['imagenes', 'user'])
            ->whereIn('id',
                SubastaPuja::where('user_id', $user->id)
                    ->pluck('producto_id')
                    ->unique()
            )
            ->where('tipo', 'subasta')
            ->where('user_id', '!=', $user->id)
            ->get()
            ->map(function ($p) use ($user) {
                $p->puja_actual   = $p->pujas()->max('monto') ?? $p->precio;
                $p->mi_mejor_puja = $p->pujas()->where('user_id', $user->id)->max('monto');
                $p->voy_ganando   = $p->pujas()->orderByDesc('monto')->value('user_id') === $user->id;
                $p->activa        = $p->estado === 'activo'
                                 && $p->timer_fin
                                 && Carbon::parse($p->timer_fin)->isFuture();

                // ¿Hay un pedido pendiente de pago por esta subasta ganada?
                $p->pedido_pendiente = Pedido::where('user_id', $user->id)
                    ->where('subasta_producto_id', $p->id)
                    ->where('estado', 'pendiente')
                    ->first();

                return $p;
            });

        return view('subastas.index', compact('publicadas', 'participando'));
    }

    // ──────────────────────────────────────────────────────
    // POST /subastas/{producto}/pujar
    // ──────────────────────────────────────────────────────
    public function pujar(Request $request, Producto $producto)
    {
        $user = Auth::user();

        if ($producto->tipo !== 'subasta') {
            return back()->with('error', 'Este producto no es una subasta.');
        }
        if ($producto->estado !== 'activo') {
            return back()->with('error', 'Esta subasta ya no está activa.');
        }
        if ($producto->user_id === $user->id) {
            return back()->with('error', 'No puedes pujar en tu propia subasta.');
        }
        if ($producto->timer_fin && Carbon::parse($producto->timer_fin)->isPast()) {
            return back()->with('error', 'El tiempo de esta subasta ha expirado.');
        }

        $pujaActual      = $producto->pujas()->max('monto') ?? $producto->precio;
        $incremento      = $producto->incremento_minimo ?? 50;
        $minimoRequerido = $pujaActual + $incremento;

        $request->validate([
            'monto' => ['required', 'numeric', "min:{$minimoRequerido}"],
        ], [
            'monto.min' => "La puja mínima es $" . number_format($minimoRequerido, 2) . " MXN.",
        ]);

        DB::transaction(function () use ($request, $producto, $user, $pujaActual) {
            SubastaPuja::create([
                'producto_id' => $producto->id,
                'user_id'     => $user->id,
                'monto'       => $request->monto,
            ]);

            $producto->update(['precio' => $request->monto]);

            // Notificar al dueño
            Notificacion::create([
                'user_id'  => $producto->user_id,
                'tipo'     => 'nueva_puja',
                'titulo'   => '¡Nueva puja en tu subasta!',
                'cuerpo'   => "\"{$producto->titulo}\" recibió una puja de $"
                              . number_format($request->monto, 2) . " MXN de {$user->name}.",
                'url'      => '/subastas',
                'ref_tipo' => 'producto',
                'ref_id'   => $producto->id,
                'leida'    => false,
            ]);

            // Notificar al pujador anterior superado
            $pujadorAnteriorId = SubastaPuja::where('producto_id', $producto->id)
                ->where('user_id', '!=', $user->id)
                ->orderByDesc('monto')
                ->value('user_id');

            if ($pujadorAnteriorId) {
                Notificacion::create([
                    'user_id'  => $pujadorAnteriorId,
                    'tipo'     => 'superado_en_subasta',
                    'titulo'   => 'Te superaron en una subasta',
                    'cuerpo'   => "Alguien hizo una puja mayor en \"{$producto->titulo}\". ¡Contraataca!",
                    'url'      => "/productos/{$producto->id}",
                    'ref_tipo' => 'producto',
                    'ref_id'   => $producto->id,
                    'leida'    => false,
                ]);
            }
        });

        return back()->with('success', '¡Puja registrada exitosamente!');
    }

    // ──────────────────────────────────────────────────────
    // GET /subastas/{producto}/historial  (JSON)
    // ──────────────────────────────────────────────────────
    public function historial(Producto $producto)
    {
        abort_if($producto->tipo !== 'subasta', 404);

        $pujas = SubastaPuja::with('user:id,name')
            ->where('producto_id', $producto->id)
            ->orderByDesc('monto')
            ->take(20)
            ->get()
            ->map(fn($p) => [
                'nombre' => $p->user->name ?? 'Anónimo',
                'monto'  => $p->monto,
                'fecha'  => $p->created_at->locale('es')->diffForHumans(),
            ]);

        return response()->json([
            'pujas'       => $pujas,
            'puja_actual' => $producto->pujas()->max('monto') ?? $producto->precio,
            'total_pujas' => $pujas->count(),
        ]);
    }

    // ──────────────────────────────────────────────────────
    // DELETE /subastas/{producto}/cancelar
    // ──────────────────────────────────────────────────────
    public function cancelar(Producto $producto)
    {
        $user = Auth::user();
        $esAdmin = $user->es_admin ?? $user->is_admin ?? $user->admin ?? false;

        if ($producto->user_id !== $user->id && !$esAdmin) abort(403);
        if ($producto->tipo !== 'subasta') {
            return back()->with('error', 'Este producto no es una subasta.');
        }

        DB::transaction(function () use ($producto, $user) {
            $producto->update(['estado' => 'pausado', 'timer_fin' => null]);

            $pujadores = SubastaPuja::where('producto_id', $producto->id)
                ->distinct('user_id')->pluck('user_id');

            foreach ($pujadores as $pujadorId) {
                if ($pujadorId !== $user->id) {
                    Notificacion::create([
                        'user_id'  => $pujadorId,
                        'tipo'     => 'subasta_cancelada',
                        'titulo'   => 'Subasta cancelada',
                        'cuerpo'   => "La subasta \"{$producto->titulo}\" fue cancelada por el vendedor.",
                        'url'      => '/subastas',
                        'ref_tipo' => 'producto',
                        'ref_id'   => $producto->id,
                        'leida'    => false,
                    ]);
                }
            }
        });

        return redirect()->route('subastas.index')
            ->with('success', 'Subasta cancelada correctamente.');
    }

    // ──────────────────────────────────────────────────────
    // PATCH /subastas/{producto}/vender
    // Cierra la subasta y crea un pedido pendiente (24h) para el ganador
    // ──────────────────────────────────────────────────────
    public function vender(Producto $producto)
    {
        $user = Auth::user();
        $esAdmin = $user->es_admin ?? $user->is_admin ?? $user->admin ?? false;

        if ($producto->user_id !== $user->id && !$esAdmin) abort(403);
        if ($producto->tipo !== 'subasta') {
            return back()->with('error', 'Este producto no es una subasta.');
        }

        $mejorPuja = SubastaPuja::where('producto_id', $producto->id)
            ->orderByDesc('monto')
            ->first();

        if (!$mejorPuja) {
            return back()->with('error', 'No hay pujas registradas. No se puede vender.');
        }

        // ¿Ya existe un pedido pendiente para esta subasta? (evitar doble envío)
        $pedidoExistente = Pedido::where('subasta_producto_id', $producto->id)
            ->whereIn('estado', ['pendiente', 'pagado'])
            ->exists();

        if ($pedidoExistente) {
            return back()->with('error', 'Esta subasta ya fue adjudicada anteriormente.');
        }

        DB::transaction(function () use ($producto, $mejorPuja, $user) {
            // Marcar puja ganadora
            $mejorPuja->update(['ganadora' => true]);

            // Congelar subasta (no recibe más pujas) pero NO marcar como vendido aún
            // Se marcará vendido cuando el ganador pague.
            $producto->update([
                'estado'    => 'adjudicado', // nuevo estado intermedio
                'timer_fin' => now(),         // terminar el countdown
            ]);

            // ── Crear pedido pendiente con 24 horas para pagar ──
            Pedido::create([
                'folio'               => Pedido::generarFolio(),
                'user_id'             => $mejorPuja->user_id,
                'subtotal'            => $mejorPuja->monto,
                'total'               => $mejorPuja->monto,
                'estado'              => 'pendiente',
                'pago_limite'         => now()->addHours(24),
                'es_subasta'          => true,
                'subasta_producto_id' => $producto->id,
            ]);

            // ── Notificar al ganador con urgencia ──
            Notificacion::create([
                'user_id'  => $mejorPuja->user_id,
                'tipo'     => 'subasta_ganada',
                'titulo'   => '🏆 ¡Ganaste la subasta!',
                'cuerpo'   => "¡Felicidades! Ganaste \"{$producto->titulo}\" con $"
                              . number_format($mejorPuja->monto, 2)
                              . " MXN. Tienes 24 horas para completar tu pago.",
                'url'      => '/subastas',
                'ref_tipo' => 'producto',
                'ref_id'   => $producto->id,
                'leida'    => false,
            ]);

            // ── Notificar a los demás pujadores ──
            $otrosPujadores = SubastaPuja::where('producto_id', $producto->id)
                ->where('user_id', '!=', $mejorPuja->user_id)
                ->distinct('user_id')->pluck('user_id');

            foreach ($otrosPujadores as $pujadorId) {
                Notificacion::create([
                    'user_id'  => $pujadorId,
                    'tipo'     => 'subasta_finalizada',
                    'titulo'   => 'Subasta finalizada',
                    'cuerpo'   => "La subasta de \"{$producto->titulo}\" fue adjudicada a otro participante.",
                    'url'      => '/subastas',
                    'ref_tipo' => 'producto',
                    'ref_id'   => $producto->id,
                    'leida'    => false,
                ]);
            }

            // ── Notificar al vendedor ──
            Notificacion::create([
                'user_id'  => $user->id,
                'tipo'     => 'venta_realizada',
                'titulo'   => 'Subasta adjudicada',
                'cuerpo'   => "Tu subasta \"{$producto->titulo}\" fue adjudicada a {$mejorPuja->user->name} por $"
                              . number_format($mejorPuja->monto, 2)
                              . " MXN. El ganador tiene 24h para pagar.",
                'url'      => '/subastas',
                'ref_tipo' => 'producto',
                'ref_id'   => $producto->id,
                'leida'    => false,
            ]);
        });

        return redirect()->route('subastas.index')
            ->with('success', '¡Subasta adjudicada! El ganador tiene 24 horas para pagar.');
    }

    // ──────────────────────────────────────────────────────
    // GET /subastas/pagar/{pedido}
    // Muestra formulario de pago para una subasta ganada
    // ──────────────────────────────────────────────────────
    public function iniciarPago(Pedido $pedido)
    {
        $user = Auth::user();

        if ($pedido->user_id !== $user->id) abort(403);
        if (!$pedido->es_subasta) abort(404);

        if ($pedido->timerExpirado()) {
            // Cancelar y notificar a ambas partes
            $this->cancelarPedidoSubasta($pedido);
            return redirect()->route('subastas.index')
                ->with('error', 'El tiempo para pagar esta subasta expiró. El vendedor fue notificado.');
        }

        $producto = $pedido->subastaProducto()->with('imagenes', 'user')->first();
        $tarjetas = \App\Models\Tarjeta::where('user_id', $user->id)
            ->orderByDesc('predeterminada')->get();

        return view('subastas.pagar', compact('pedido', 'producto', 'tarjetas'));
    }

    // ──────────────────────────────────────────────────────
    // POST /subastas/pagar/{pedido}/procesar
    // ──────────────────────────────────────────────────────
    public function procesarPago(Request $request, Pedido $pedido)
    {
        $user = Auth::user();

        if ($pedido->user_id !== $user->id) abort(403);
        if (!$pedido->es_subasta) abort(404);

        if ($pedido->timerExpirado()) {
            $this->cancelarPedidoSubasta($pedido);
            return redirect()->route('subastas.index')
                ->with('error', 'El tiempo para pagar expiró.');
        }

        // Determinar tarjeta
        $usarGuardada = $request->filled('tarjeta_guardada_id');

        if ($usarGuardada) {
            $tarjeta = \App\Models\Tarjeta::where('id', $request->tarjeta_guardada_id)
                ->where('user_id', $user->id)->firstOrFail();
        } else {
            $request->validate([
                'numero'   => ['required', 'regex:/^[\d\s]{13,23}$/'],
                'titular'  => ['required', 'string', 'max:100'],
                'exp_mes'  => ['required', 'integer', 'between:1,12'],
                'exp_anio' => ['required', 'integer', 'min:' . date('Y')],
                'cvv'      => ['required', 'digits_between:3,4'],
            ]);

            $numeroLimpio = preg_replace('/\D/', '', $request->numero);
            $tarjeta = null;

            if ($request->boolean('guardar_tarjeta')) {
                if ($request->boolean('predeterminada')) {
                    \App\Models\Tarjeta::where('user_id', $user->id)->update(['predeterminada' => false]);
                }
                $tarjeta = \App\Models\Tarjeta::create([
                    'user_id'        => $user->id,
                    'ultimos_cuatro' => substr($numeroLimpio, -4),
                    'tipo'           => \App\Models\Tarjeta::detectarTipo($numeroLimpio),
                    'titular'        => strtoupper(trim($request->titular)),
                    'exp_mes'        => $request->exp_mes,
                    'exp_anio'       => $request->exp_anio,
                    'token_simulado' => \App\Models\Tarjeta::generarToken(),
                    'predeterminada' => $request->boolean('predeterminada'),
                ]);
            }
        }

        $producto = $pedido->subastaProducto;

        DB::transaction(function () use ($pedido, $producto, $tarjeta, $request, $usarGuardada, $user) {
            $numeroLimpio = $usarGuardada ? null : preg_replace('/\D/', '', $request->numero ?? '');

            $pedido->update([
                'tarjeta_id'             => $tarjeta?->id,
                'tarjeta_ultimos_cuatro' => $tarjeta?->ultimos_cuatro ?? substr($numeroLimpio, -4),
                'tarjeta_tipo'           => $tarjeta?->tipo ?? \App\Models\Tarjeta::detectarTipo($numeroLimpio ?? ''),
                'estado'                 => 'pagado',
                'pagado_at'              => now(),
            ]);

            // Crear pedido_item
            \App\Models\PedidoItem::create([
                'pedido_id'       => $pedido->id,
                'producto_id'     => $producto->id,
                'titulo'          => $producto->titulo,
                'tipo_accion'     => 'comprar',
                'precio_unitario' => $pedido->total,
                'cantidad'        => 1,
                'total_item'      => $pedido->total,
                'vendedor_id'     => $producto->user_id,
            ]);

            // Marcar producto como vendido
            $producto->update(['estado' => 'vendido']);

            // Notificar al ganador (comprador)
            Notificacion::create([
                'user_id'  => $user->id,
                'tipo'     => 'compra_confirmada',
                'titulo'   => '¡Pago confirmado! ' . $pedido->folio,
                'cuerpo'   => "Tu pago de $" . number_format($pedido->total, 2)
                              . " MXN por \"{$producto->titulo}\" fue procesado. ¡El vendedor se pondrá en contacto!",
                'url'      => '/dashboard/compras',
                'ref_tipo' => 'pedido',
                'ref_id'   => $pedido->id,
                'leida'    => false,
            ]);

            // Notificar al vendedor
            Notificacion::create([
                'user_id'  => $producto->user_id,
                'tipo'     => 'venta_realizada',
                'titulo'   => '¡Pago recibido por tu subasta!',
                'cuerpo'   => "{$user->name} pagó $" . number_format($pedido->total, 2)
                              . " MXN por \"{$producto->titulo}\".",
                'url'      => '/dashboard/ventas',
                'ref_tipo' => 'pedido',
                'ref_id'   => $pedido->id,
                'leida'    => false,
            ]);
        });

        return redirect()->route('pago.confirmacion', $pedido)
            ->with('success', "¡Pago exitoso! Pedido #{$pedido->folio} confirmado.");
    }

    // ──────────────────────────────────────────────────────
    // GET /subastas/pagar/{pedido}/timer  (AJAX)
    // ──────────────────────────────────────────────────────
    public function timerSubasta(Pedido $pedido)
    {
        if ($pedido->user_id !== Auth::id()) abort(403);

        $expirado = $pedido->timerExpirado();

        if ($expirado && $pedido->estado === 'pendiente') {
            $this->cancelarPedidoSubasta($pedido);
        }

        return response()->json([
            'expirado' => $expirado,
            'segundos' => $pedido->segundosRestantes(),
        ]);
    }

    // ──────────────────────────────────────────────────────
    // Helper privado: cancelar pedido de subasta expirado
    // ──────────────────────────────────────────────────────
    private function cancelarPedidoSubasta(Pedido $pedido): void
    {
        if ($pedido->estado !== 'pendiente') return;

        $pedido->update(['estado' => 'cancelado']);
        $producto = $pedido->subastaProducto;

        if ($producto) {
            // Devolver subasta a estado activo para que el vendedor pueda re-adjudicar
            $producto->update(['estado' => 'adjudicado_expirado']);

            // Notificar al ganador
            Notificacion::create([
                'user_id'  => $pedido->user_id,
                'tipo'     => 'pago_subasta_expirado',
                'titulo'   => 'Tiempo de pago expirado',
                'cuerpo'   => "Tu tiempo para pagar \"{$producto->titulo}\" expiró. El vendedor ha sido notificado.",
                'url'      => '/subastas',
                'ref_tipo' => 'pedido',
                'ref_id'   => $pedido->id,
                'leida'    => false,
            ]);

            // Notificar al vendedor
            Notificacion::create([
                'user_id'  => $producto->user_id,
                'tipo'     => 'pago_subasta_expirado',
                'titulo'   => 'El ganador no pagó a tiempo',
                'cuerpo'   => "El ganador de \"{$producto->titulo}\" no completó el pago en 24h. Puedes vender al siguiente postor.",
                'url'      => '/subastas',
                'ref_tipo' => 'producto',
                'ref_id'   => $producto->id,
                'leida'    => false,
            ]);
        }
    }
}