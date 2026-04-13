<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    /**
     * Lista paginada de productos con oferta activa.
     * 20 productos por página, con filtros opcionales de categoría y tipo.
     */
    public function index(Request $request)
    {
        $query = Producto::with(['imagenPrincipal', 'categoria'])
            ->where('estado', 'activo')
            ->conOferta()
            ->orderByDesc('descuento_porcentaje'); // mayor descuento primero

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->whereHas('categoria', fn($q) =>
                $q->where('slug', $request->categoria)
            );
        }

        // Filtro por tipo (renta / venta / subasta)
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtro por descuento mínimo
        if ($request->filled('descuento_min')) {
            $query->where('descuento_porcentaje', '>=', (int) $request->descuento_min);
        }

        $ofertas    = $query->paginate(20)->withQueryString();
        $categorias = Categoria::where('estado', 'activo')->orderBy('nombre')->get();

        return view('ofertas.index', compact('ofertas', 'categorias'));
    }
}