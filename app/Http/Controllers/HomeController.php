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
}
