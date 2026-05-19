<?php

namespace App\Http\Middleware;

use App\Services\PlanService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verifica que el usuario no haya superado el límite de publicaciones de su plan.
 *
 * Si el límite está alcanzado:
 *  - En peticiones GET → redirige con mensaje de alerta
 *  - En peticiones POST/PATCH → también redirige con alerta
 */
class VerificarLimitePlan
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Solo aplicar en rutas de publicar (GET del formulario y POST de store)
        if (!PlanService::puedePublicar($user)) {
            $config    = PlanService::deUsuario($user);
            $plan      = $user->plan ?? 'free';
            $limite    = $config['publicaciones'];
            $actuales  = $user->productos()->where('estado', 'activo')->count();

            $mensaje = "Has alcanzado el límite de {$limite} publicaciones activas de tu plan {$config['label']}. "
                     . "Pausa o elimina una publicación existente, o mejora tu plan para continuar.";

            if ($request->expectsJson()) {
                return response()->json([
                    'error'          => $mensaje,
                    'limite'         => $limite,
                    'actuales'       => $actuales,
                    'upgrade_url'    => route('planes.index'),
                ], 403);
            }

            return redirect()->route('mis-publicaciones.index')
                ->with('plan_limite', $mensaje)
                ->with('plan_actual', $plan);
        }

        return $next($request);
    }
}