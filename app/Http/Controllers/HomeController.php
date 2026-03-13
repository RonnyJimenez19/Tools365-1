<?php

// Landing page controller

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class HomeController extends Controller
{
    public function inicio()
    {
        // Helper: 4 productos activos del tipo dado, con imagen y categoría
        $porTipo = fn(string $tipo) => Producto::with(['categoria', 'imagenes'])
            ->where('estado', 'activo')
            ->where('tipo', $tipo)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Subastas: las más urgentes primero (timer_fin más cercano)
        $subastas = Producto::with(['categoria', 'imagenes'])
            ->where('estado', 'activo')
            ->where('tipo', 'subasta')
            ->whereNotNull('timer_fin')
            ->where('timer_fin', '>', now())
            ->orderBy('timer_fin', 'asc')
            ->take(4)
            ->get();

        $rentas = $porTipo('renta');
        $ventas = $porTipo('venta');

        return view('inicio', compact('subastas', 'rentas', 'ventas'));
    }

    public function buscar(Request $request)
    {
        $termino = trim($request->get('q', ''));
        $tipo    = $request->get('tipo', '');

        // Si no hay término ni filtro de tipo, no ejecutar consulta
        $buscando = $termino !== '' || $tipo !== '';

        $productos = null;

        if ($buscando) {
            $productos = Producto::with(['categoria', 'imagenes'])
                ->where('estado', 'activo')
                ->buscar($termino)
                ->when($tipo, fn($q) => $q->where('tipo', $tipo))
                ->orderBy('created_at', 'desc')
                ->paginate(12)
                ->withQueryString();
        }

        return view('busqueda', compact('productos', 'termino', 'tipo', 'buscando'));
    }

    public function busquedaAvanzada(Request $request)
    {
        $termino       = $request->get('q', '');
        $categoriaSlug = $request->get('categoria', '');
        $tipo          = $request->get('tipo', '');
        $precioMin     = $request->get('precio_min', '');
        $precioMax     = $request->get('precio_max', '');

        // Catálogo de categorías desde la BD
        $categorias = Categoria::where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        // ¿Se envió el formulario con algún parámetro?
        $buscando = $request->hasAny(['q', 'categoria', 'tipo', 'precio_min', 'precio_max']);

        if ($buscando) {
            $productos = Producto::with(['categoria', 'imagenes'])
                ->where('estado', 'activo')
                ->buscar($termino)
                ->when($categoriaSlug, fn($q) =>
                    $q->whereHas('categoria', fn($c) => $c->where('slug', $categoriaSlug))
                )
                ->when($tipo,      fn($q) => $q->where('tipo', $tipo))
                ->when($precioMin, fn($q) => $q->where('precio', '>=', $precioMin))
                ->when($precioMax, fn($q) => $q->where('precio', '<=', $precioMax))
                ->orderBy('created_at', 'desc')
                ->paginate(12)
                ->withQueryString();
        } else {
            // Sin filtros: mostrar los últimos 20 productos registrados
            $productos = Producto::with(['categoria', 'imagenes'])
                ->where('estado', 'activo')
                ->orderBy('created_at', 'desc')
                ->take(20)
                ->get();
        }

        return view('busqueda-avanzada', compact(
            'productos', 'termino', 'categoriaSlug',
            'tipo', 'precioMin', 'precioMax',
            'categorias', 'buscando'
        ));
    }
}