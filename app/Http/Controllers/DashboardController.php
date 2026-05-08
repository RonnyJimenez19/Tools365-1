<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ProductoImagen;
use App\Models\ProductoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    
public function index()
{
    $user = Auth::user();

    if ($user->rol === 'admin') {
        return view('dashboard.admin');   // vista admin
    }

    if ($user->rol === 'gerente') {
        return view('dashboard.gerente'); // si lo necesitas después
    }

    return view('dashboard.index');       // usuario normal (el que ya tienes)
}
}
