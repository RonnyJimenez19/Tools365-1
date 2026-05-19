<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Tarjeta;
use App\Services\PlanService;
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
     */
    public function index()
    {
        $user = Auth::user();
        return view('dashboard.plan', compact('user'));
    }

    /**
     * GET /dashboard/plan/checkout/{plan}
     */
    public function checkout(string $plan)
    {
        $user = Auth::user();

        $planesValidos = ['basico', 'profesional'];
        if (!in_array($plan, $planesValidos)) {
            return redirect()->route('dashboard.plan')->with('error', 'Plan no válido.');
        }

        // No puede "mejorar" al mismo plan o inferior
        // El plan base ahora es 'free' (antes era null)
        $planActual = $user->plan ?? 'free';
        if (!PlanService::esSuperior($plan, $planActual)) {
            return redirect()->route('dashboard.plan')
                ->with('error', 'Ya tienes un plan igual o superior.');
        }

        $infoPlan = PlanService::de($plan);
        $tarjetas = Tarjeta::where('user_id', $user->id)
            ->orderByDesc('predeterminada')
            ->get();

        // Precios para la vista
        $precios = [
            'mensual'      => PlanService::precioMensual($plan),
            'anual_total'  => PlanService::precioAnualTotal($plan),
            'mensual_eq'   => PlanService::precioMensualEquivalenteAnual($plan),
            'ahorro_anual' => PlanService::ahorroAnual($plan),
        ];

        return view('dashboard.plan-checkout', compact('plan', 'infoPlan', 'tarjetas', 'user', 'precios'));
    }

    /**
     * POST /dashboard/plan/pagar
     */
    public function pagar(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'plan'    => 'required|in:basico,profesional',
            'periodo' => 'required|in:mensual,anual',
        ]);

        $plan       = $request->plan;
        $periodo    = $request->periodo;
        $planActual = $user->plan ?? 'free';

        // Verificar jerarquía
        if (!PlanService::esSuperior($plan, $planActual)) {
            return back()->with('error', 'Ya tienes un plan igual o superior.');
        }

        // ── Calcular monto ──────────────────────────────────────────────────
        $monto = $periodo === 'anual'
            ? PlanService::precioAnualTotal($plan)
            : PlanService::precioMensual($plan);

        // ── Determinar tarjeta ──────────────────────────────────────────────
        $usarTarjetaGuardada = $request->filled('tarjeta_guardada_id');

        if ($usarTarjetaGuardada) {
            $tarjeta = Tarjeta::where('id', $request->tarjeta_guardada_id)
                ->where('user_id', $user->id)
                ->firstOrFail();
            $ultimosCuatro = $tarjeta->ultimos_cuatro;
            $tipoTarjeta   = $tarjeta->tipo;
        } else {
            $request->validate([
                'numero'   => ['required', 'regex:/^[\d\s]{13,23}$/'],
                'titular'  => ['required', 'string', 'max:100'],
                'exp_mes'  => ['required', 'integer', 'between:1,12'],
                'exp_anio' => ['required', 'integer', 'min:' . date('Y')],
                'cvv'      => ['required', 'digits_between:3,4'],
            ], [
                'numero.required'   => 'El número de tarjeta es obligatorio.',
                'titular.required'  => 'El nombre del titular es obligatorio.',
                'exp_mes.required'  => 'El mes de vencimiento es obligatorio.',
                'exp_anio.required' => 'El año de vencimiento es obligatorio.',
                'cvv.required'      => 'El CVV es obligatorio.',
            ]);

            $numeroLimpio  = preg_replace('/\D/', '', $request->numero);
            $ultimosCuatro = substr($numeroLimpio, -4);
            $tipoTarjeta   = Tarjeta::detectarTipo($numeroLimpio);

            $tarjeta = null;
            if ($request->boolean('guardar_tarjeta')) {
                if ($request->boolean('predeterminada')) {
                    Tarjeta::where('user_id', $user->id)->update(['predeterminada' => false]);
                }
                $tarjeta = Tarjeta::create([
                    'user_id'        => $user->id,
                    'ultimos_cuatro' => $ultimosCuatro,
                    'tipo'           => $tipoTarjeta,
                    'titular'        => strtoupper(trim($request->titular)),
                    'exp_mes'        => $request->exp_mes,
                    'exp_anio'       => $request->exp_anio,
                    'token_simulado' => Tarjeta::generarToken(),
                    'predeterminada' => $request->boolean('predeterminada'),
                ]);
            }
        }

        // ── Guardar en DB y actualizar plan ─────────────────────────────────
        DB::beginTransaction();
        try {
            $folio = 'PLAN-' . strtoupper(substr(md5(uniqid()), 0, 8));

            Pedido::create([
                'folio'                  => $folio,
                'user_id'                => $user->id,
                'tarjeta_id'             => $tarjeta?->id,
                'tarjeta_ultimos_cuatro' => $ultimosCuatro,
                'tarjeta_tipo'           => $tipoTarjeta,
                'subtotal'               => $monto,
                'total'                  => $monto,
                'comision_total'         => 0,
                'estado'                 => 'pagado',
                'pago_limite'            => now()->addMinutes(5),
                'pagado_at'              => now(),
            ]);

            $user->update(['plan' => $plan]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error al procesar el pago. Intenta de nuevo.');
        }

        $infoPlan = PlanService::de($plan);
        $label    = "{$infoPlan['emoji']} {$infoPlan['label']}";

        return redirect()->route('dashboard.plan')
            ->with('success', "¡Plan actualizado a {$label}! Tu suscripción está activa.");
    }

    /**
     * DELETE /dashboard/plan/cancelar
     */
    public function cancelar()
    {
        $user       = Auth::user();
        $planActual = $user->plan ?? 'free';

        if ($planActual === 'free') {
            return back()->with('error', 'Ya estás en el plan gratuito.');
        }

        $user->update(['plan' => 'free']);

        return redirect()->route('dashboard.plan')
            ->with('success', 'Tu suscripción fue cancelada. Ahora estás en el Plan Free gratuito.');
    }
}