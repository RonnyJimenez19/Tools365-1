<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function show(Producto $producto)
    {
        // Solo productos activos son visibles públicamente
        abort_if($producto->estado !== 'activo', 404);

        $producto->load(['imagenes', 'categoria', 'detalles', 'user']);

        // Productos relacionados: misma categoría, mismo tipo, excluyendo el actual
        $relacionados = Producto::with(['imagenes', 'categoria'])
            ->where('estado', 'activo')
            ->where('id', '!=', $producto->id)
            ->where(function ($q) use ($producto) {
                $q->where('categoria_id', $producto->categoria_id)
                  ->orWhere('tipo', $producto->tipo);
            })
            ->latest()
            ->take(4)
            ->get();

        return view('productos.show', compact('producto', 'relacionados'));
    }
}