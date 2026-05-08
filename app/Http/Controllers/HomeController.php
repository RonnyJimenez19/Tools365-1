<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
{
    public function inicio()
    {
        $subastas = Producto::with(['imagenes', 'categoria'])
            ->where('tipo', 'subasta')
            ->where('estado', 'activo')
            ->latest()
            ->take(4)
            ->get();

        $rentas = Producto::with(['imagenes', 'categoria'])
            ->where('tipo', 'renta')
            ->where('estado', 'activo')
            ->latest()
            ->take(4)
            ->get();

        $ventas = Producto::with(['imagenes', 'categoria'])
            ->where('tipo', 'venta')
            ->where('estado', 'activo')
            ->latest()
            ->take(4)
            ->get();

        // Comentarios aprobados y marcados como "en inicio" (máx. 3)
        $comentarios_inicio = Comentario::aprobados()
            ->enInicio()
            ->latest()
            ->take(3)
            ->get();

        // Si no hay suficientes destacados, completar con los más recientes aprobados
        if ($comentarios_inicio->count() < 3) {
            $ids   = $comentarios_inicio->pluck('id');
            $extra = Comentario::aprobados()
                ->whereNotIn('id', $ids)
                ->latest()
                ->take(3 - $comentarios_inicio->count())
                ->get();

            $comentarios_inicio = $comentarios_inicio->concat($extra);
        }

        return view('inicio', compact(
            'subastas', 'rentas', 'ventas', 'comentarios_inicio'
        ));
    }

    public function buscar(Request $request)
    {
        $query = $request->get('q', '');
        $tipo  = $request->get('tipo', '');

        $productos = Producto::with(['imagenes', 'categoria'])
            ->where('estado', 'activo')
            ->where(function ($q) use ($query) {
                $q->where('titulo', 'like', "%{$query}%")
                  ->orWhere('descripcion', 'like', "%{$query}%");
            })
            ->when($tipo, fn($q) => $q->where('tipo', $tipo))
            ->paginate(12)
            ->withQueryString();

        return view('busqueda.busqueda', [
            'productos' => $productos,
            'termino'   => $query,
            'buscando'  => true,
            'tipo'      => $tipo,
        ]);
    }

public function busquedaAvanzada(Request $request)
{
    $termino       = $request->get('q', '');
    $tipo          = $request->get('tipo', '');
    $categoriaSlug = $request->get('categoria', '');

    $categorias = \App\Models\Categoria::where('estado', 'activo')->get();

    $buscando = $request->hasAny(['q', 'tipo', 'categoria', 'precio_min', 'precio_max']);

    $productosQuery = Producto::with(['imagenes', 'categoria'])
        ->where('estado', 'activo')
        ->when($termino, fn($q) =>
            $q->where('titulo', 'like', "%{$termino}%")
              ->orWhere('descripcion', 'like', "%{$termino}%"))
        ->when($tipo, fn($q) => $q->where('tipo', $tipo))
        ->when($categoriaSlug, fn($q) =>
            $q->whereHas('categoria', fn($q) => $q->where('slug', $categoriaSlug)))
        ->when($request->filled('precio_min'), fn($q) =>
            $q->where('precio', '>=', $request->precio_min))
        ->when($request->filled('precio_max'), fn($q) =>
            $q->where('precio', '<=', $request->precio_max))
        ->latest();

    // Sin filtros: solo los últimos 36, paginados de 12 en 12 (máx. 3 páginas)
    // Con filtros: todos los resultados paginados de 12 en 12
    if (!$buscando) {
        $perPage      = 12;
        $currentPage  = LengthAwarePaginator::resolveCurrentPage();
        $allItems     = $productosQuery->take(36)->get();
        $currentItems = $allItems->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $productos = new LengthAwarePaginator(
            $currentItems,
            $allItems->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    } else {
        $productos = $productosQuery->paginate(12)->withQueryString();
    }

    return view('busqueda.busqueda-avanzada', [
        'productos'     => $productos,
        'termino'       => $termino,
        'tipo'          => $tipo,
        'categoriaSlug' => $categoriaSlug,
        'categorias'    => $categorias,
        'precioMin'     => $request->get('precio_min', ''),
        'precioMax'     => $request->get('precio_max', ''),
        'buscando'      => $buscando,
    ]);
}
}