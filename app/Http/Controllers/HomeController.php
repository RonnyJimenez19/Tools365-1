<?php

// Landing page controller

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class HomeController extends Controller
{
    public function inicio()
    {
        return view('inicio');
    }

    public function buscar(Request $request)
    {
        $termino = $request->get('q', '');
        $tipo    = $request->get('tipo', '');

        $productos = Producto::where('estado', 'activo')
            ->buscar($termino)
            ->when($tipo, fn($q) => $q->where('tipo', $tipo))
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('busqueda', compact('productos', 'termino', 'tipo'));
    }

    public function busquedaAvanzada(Request $request)
    {
        $termino   = $request->get('q', '');
        $categoria = $request->get('categoria', '');
        $tipo      = $request->get('tipo', '');
        $precioMin = $request->get('precio_min', '');
        $precioMax = $request->get('precio_max', '');

        // Lista de categorías para el combo
        $categorias = [
            'construccion' => 'Construcción',
            'agricultura'  => 'Agricultura',
            'ganaderia'    => 'Ganadería',
            'alimentos'    => 'Alimentos',
            'plomeria'     => 'Plomería',
            'electricidad' => 'Electricidad',
            'carpinteria'  => 'Carpintería',
            'jardineria'   => 'Jardinería',
            'soldadura'    => 'Soldadura',
            'pintura'      => 'Pintura',
            'transporte'   => 'Transporte',
            'otros'        => 'Otros',
        ];

        // Solo ejecutar búsqueda si se envió el formulario (hay algún parámetro)
        $buscando = $request->hasAny(['q', 'categoria', 'tipo', 'precio_min', 'precio_max']);

        $productos = null;

        if ($buscando) {
            $query = Producto::where('estado', 'activo')
                ->buscar($termino)
                ->when($categoria, fn($q) => $q->where('categoria', $categoria))
                ->when($tipo,      fn($q) => $q->where('tipo', $tipo))
                ->when($precioMin, fn($q) => $q->where('precio', '>=', $precioMin))
                ->when($precioMax, fn($q) => $q->where('precio', '<=', $precioMax))
                ->orderBy('created_at', 'desc');

            $productos = $query->paginate(12)->withQueryString();
        }

        return view('busqueda-avanzada', compact(
            'productos', 'termino', 'categoria',
            'tipo', 'precioMin', 'precioMax',
            'categorias', 'buscando'
        ));
    }
}