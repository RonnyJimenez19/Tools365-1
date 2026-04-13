<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ── Alias de middlewares ─────────────────────────────────────────────
        $middleware->alias([
            'rol'             => \App\Http\Middleware\RoleMiddleware::class,
            'session.timeout' => \App\Http\Middleware\SessionTimeout::class,
        ]);

        // ── Aplicar timeout de sesión a todas las rutas web ──────────────────
        // Solo afecta a usuarios autenticados (el middleware hace la verificación)
        $middleware->appendToGroup('web', \App\Http\Middleware\SessionTimeout::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();