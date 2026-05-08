<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Categoria;
use App\Http\View\Composers\NavComposer;
use Illuminate\Pagination\Paginator; 

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        // Inyecta $categoriasNav en el partial del navbar
        View::composer('partials.navbar', function ($view) {
            $view->with('categoriasNav', Categoria::where('estado', 'activo')
                ->orderBy('nombre')
                ->get());
        });

        // Inyecta $navItems (desde nav_items en BD, ordenados por `orden`) en el partial
        View::composer('partials.navbar', NavComposer::class);
    }
}