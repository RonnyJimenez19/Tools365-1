<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuentaActiva
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $status = Auth::user()->status;

            if ($status === 'bloqueado') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Tu cuenta ha sido bloqueada por uso indebido. Si crees que es un error, contacta al soporte.'])
                    ->withInput($request->only('email'));
            }

            if ($status === 'inactivo') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Esta cuenta está inactiva. Contacta al administrador para reactivarla.'])
                    ->withInput($request->only('email'));
            }
        }

        return $next($request);
    }
}