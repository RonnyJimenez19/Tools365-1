<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Tarjeta;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlanController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * GET /dashboard/plan
     * Muestra el plan actual del usuario y los planes disponibles.
     */
    public function index()
    {
        $user = Auth::user();
        return view('dashboard.plan', compact('user'));
    }

    /**
     * GET /dashboard/plan/checkout/{plan}
     * Muestra el formulario de pago para suscribirse a un plan.
     */
    public function checkout(string $plan)
    {
        $user = Auth::user();

        $planesValidos = ['pro', 'empresarial'];
        if (!in_array($plan, $planesValidos)) {
            return redirect()->route('dashboard.plan')->with('error', 'Plan no válido.');
        }

        // No puede "mejorar" al mismo plan o a uno inferior
        $jerarquia = ['basico' => 0, 'pro' => 1, 'empresarial' => 2];
        if (($jerarquia[$plan] ?? 0) <= ($jerarquia[$user->plan] ?? 0)) {
            return redirect()->route('dashboard.plan')->with('error', 'Ya tienes un plan igual o superior.');
        }

        $precios = [
            'pro'         => ['mensual' => 299,  'anual' => 239,  'label' => 'Pro',         'emoji' => '🚀'],
            'empresarial' => ['mensual' => 799,  'anual' => 639,  'label' => 'Empresarial',  'emoji' => '👑'],
        ];

        $infoPlan = $precios[$plan];

        $tarjetas = Tarjeta::where('user_id', $user->id)
            ->orderByDesc('predeterminada')
            ->get();

        return view('dashboard.plan-checkout', compact('plan', 'infoPlan', 'tarjetas', 'user'));
    }

    /**
     * POST /dashboard/plan/pagar
     * Procesa el pago de suscripción y actualiza el plan del usuario.
     */
    public function pagar(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'plan'     => 'required|in:pro,empresarial',
            'periodo'  => 'required|in:mensual,anual',
        ]);

        $plan    = $request->plan;
        $periodo = $request->periodo;

        $precios = [
            'pro'         => ['mensual' => 299,  'anual' => 239],
            'empresarial' => ['mensual' => 799,  'anual' => 639],
        ];

        $monto = $precios[$plan][$periodo];

        // ── Determinar método de pago ─────────────────────────────────────
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
            ]);

            $numeroLimpio  = preg_replace('/\D/', '', $request->numero);
            $ultimosCuatro = substr($numeroLimpio, -4);
            $tipoDetectado = Tarjeta::detectarTipo($numeroLimpio);

            $tarjeta = null;
            if ($request->boolean('guardar_tarjeta')) {
                if ($request->boolean('predeterminada')) {
                    Tarjeta::where('user_id', $user->id)->update(['predeterminada' => false]);
                }

                $tarjeta = Tarjeta::create([
                    'user_id'        => $user->id,
                    'ultimos_cuatro' => $ultimosCuatro,
                    'tipo'           => $tipoDetectado,
                    'titular'        => strtoupper(trim($request->titular)),
                    'exp_mes'        => $request->exp_mes,
                    'exp_anio'       => $request->exp_anio,
                    'token_simulado' => Tarjeta::generarToken(),
                    'predeterminada' => $request->boolean('predeterminada'),
                ]);
            }
        }

        // ── Actualizar plan del usuario ───────────────────────────────────
        DB::beginTransaction();
        try {
            // Crear folio de "pedido de plan" para historial
            $folio = 'PLAN-' . strtoupper(substr(md5(uniqid()), 0, 8));

            // Guardar en pedidos con tipo especial (es_suscripcion = true si tienes ese campo,
            // sino usamos nota en el folio como convención simple)
            Pedido::create([
                'folio'                  => $folio,
                'user_id'                => $user->id,
                'tarjeta_id'             => $tarjeta?->id,
                'tarjeta_ultimos_cuatro' => $tarjeta?->ultimos_cuatro
                    ?? substr(preg_replace('/\D/', '', $request->numero ?? ''), -4),
                'tarjeta_tipo'           => $tarjeta?->tipo
                    ?? (isset($numeroLimpio) ? Tarjeta::detectarTipo($numeroLimpio) : null),
                'subtotal'               => $monto,
                'total'                  => $monto,
                'estado'                 => 'pagado',
                'pago_limite'            => now()->addMinutes(5),
                'pagado_at'              => now(),
            ]);

            // Actualizar el plan del usuario
            $user->update(['plan' => $plan]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error al procesar el pago. Intenta de nuevo.');
        }

        $planLabel = $plan === 'pro' ? '🚀 Pro' : '👑 Empresarial';

        return redirect()->route('dashboard.plan')
            ->with('success', "¡Plan actualizado a {$planLabel}! Tu suscripción está activa.");
    }

    /**
     * DELETE /dashboard/plan/cancelar
     * Cancela la suscripción (regresa a básico al vencer).
     */
    public function cancelar()
    {
        $user = Auth::user();

        if ($user->plan === 'basico') {
            return back()->with('error', 'Ya estás en el plan gratuito.');
        }

        // En producción marcarías una fecha de cancelación pendiente
        // Aquí simplemente regresamos a básico de forma inmediata (puedes ajustar)
        $user->update(['plan' => 'basico']);

        return redirect()->route('dashboard.plan')
            ->with('success', 'Tu suscripción fue cancelada. Ahora estás en el Plan Básico gratuito.');
    }
}