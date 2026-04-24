<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Minutos de inactividad antes de cerrar la sesión.
     */
    protected int $timeoutMinutes = 12;

public function handle(Request $request, Closure $next): Response
{
    if (Auth::check()) {
        $lastActivity = session('last_activity_at');


        if ($lastActivity !== null) {
$inactiveSeconds = now()->diffInSeconds($lastActivity, false) * -1;

                if ($inactiveSeconds > ($this->timeoutMinutes * 60)) {
                    // Determinar a dónde redirigir según el rol antes de cerrar sesión
                    $wasAdmin = false; // TODO: Implement proper admin check, e.g., Auth::user()->puedeEditar() if defined

                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    $route    = $wasAdmin ? 'admin.login' : 'login';
                    $mensaje  = 'Tu sesión expiró por inactividad ('
                        . $this->timeoutMinutes
                        . ' minutos). Por favor vuelve a iniciar sesión.';

                    return redirect()->route($route)
                        ->with('session_expired', $mensaje);
                }
            }

            // Actualizar marca de tiempo de última actividad
            session(['last_activity_at' => now()]);
        }

        return $next($request);
    }
}