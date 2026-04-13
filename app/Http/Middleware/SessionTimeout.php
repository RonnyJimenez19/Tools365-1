<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Minutos de inactividad antes de cerrar la sesión.
     */
    protected int $timeoutMinutes = 1;

public function handle(Request $request, Closure $next): Response
{
    if (Auth::check()) {
        $lastActivity = session('last_activity_at');

        // DEBUG TEMPORAL — quítalo después
        \Log::info('Session timeout check', [
            'user'          => Auth::user()->email,
            'last_activity' => $lastActivity,
            'now'           => now(),
            'diff_seconds'  => $lastActivity ? now()->diffInSeconds($lastActivity) : 'NULL',
            'timeout'       => $this->timeoutMinutes * 60,
        ]);

        if ($lastActivity !== null) {
$inactiveSeconds = now()->diffInSeconds($lastActivity, false) * -1;

                if ($inactiveSeconds > ($this->timeoutMinutes * 60)) {
                    // Determinar a dónde redirigir según el rol antes de cerrar sesión
                    $wasAdmin = Auth::user()->puedeEditar();

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