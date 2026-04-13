<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Producto;
use Illuminate\Http\Request;

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

        $productos = Producto::with(['imagenes', 'categoria'])
            ->where('estado', 'activo')
            ->where(function ($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                  ->orWhere('descripcion', 'like', "%{$query}%");
            })
            ->paginate(12)
            ->withQueryString();

        return view('busqueda', compact('productos', 'query'));
    }

    public function busquedaAvanzada(Request $request)
    {
        $productos = Producto::with(['imagenes', 'categoria'])
            ->where('estado', 'activo')
            ->when($request->filled('q'), fn($q) =>
                $q->where('nombre', 'like', '%' . $request->q . '%'))
            ->when($request->filled('tipo'), fn($q) =>
                $q->where('tipo', $request->tipo))
            ->when($request->filled('categoria_id'), fn($q) =>
                $q->where('categoria_id', $request->categoria_id))
            ->when($request->filled('precio_min'), fn($q) =>
                $q->where('precio', '>=', $request->precio_min))
            ->when($request->filled('precio_max'), fn($q) =>
                $q->where('precio', '<=', $request->precio_max))
            ->paginate(12)
            ->withQueryString();

        return view('busqueda-avanzada', compact('productos'));
    }
}