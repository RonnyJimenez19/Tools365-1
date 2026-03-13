<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Categoria;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Inyecta $categoriasNav en TODAS las vistas que usen layouts.app
        // Así el dropdown del header siempre refleja lo que hay en la BD
        View::composer('layouts.app', function ($view) {
            $view->with('categoriasNav', Categoria::where('estado', 'activo')
                ->orderBy('nombre')
                ->get());
        });
    }
}