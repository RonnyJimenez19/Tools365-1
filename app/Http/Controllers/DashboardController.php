<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ProductoImagen;
use App\Models\ProductoDetalle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->rol === 'admin') {
            // Datos reales para el panel admin
            $stats = [
                'usuarios'     => User::count(),
                'publicaciones' => Producto::where('estado', 'activo')->count(),
                'subastas'     => Producto::where('tipo', 'subasta')->where('estado', 'activo')->count(),
            ];

            // Actividad reciente: mezcla de usuarios y publicaciones recientes
            $usuariosRecientes    = User::orderBy('created_at', 'desc')->take(5)->get();
            $publicacionesRecientes = Producto::with('user')->orderBy('created_at', 'desc')->take(5)->get();

            $actividad = collect();

            foreach ($usuariosRecientes as $u) {
                $actividad->push([
                    'tipo'  => 'usuario',
                    'icono' => 'bi-person-check-fill',
                    'color' => 'green',
                    'msg'   => "<strong>{$u->name}</strong> se registró en la plataforma",
                    'fecha' => $u->created_at,
                    'time'  => $u->created_at->diffForHumans(),
                    'cta'   => null,
                ]);
            }

            foreach ($publicacionesRecientes as $p) {
                $actividad->push([
                    'tipo'  => 'publicacion',
                    'icono' => 'bi-box-seam-fill',
                    'color' => 'blue',
                    'msg'   => "<strong>{$p->user?->name}</strong> publicó \"{$p->titulo}\"",
                    'fecha' => $p->created_at,
                    'time'  => $p->created_at->diffForHumans(),
                    'cta'   => null,
                ]);
            }

            $actividad = $actividad->sortByDesc('fecha')->take(8)->values();

            // Últimos 5 usuarios registrados
            $ultimosUsuarios = User::orderBy('created_at', 'desc')->take(5)->get();

            // Últimas 5 publicaciones
            $ultimasPublicaciones = Producto::with('user')->orderBy('created_at', 'desc')->take(5)->get();

            return view('dashboard.admin', compact('stats', 'actividad', 'ultimosUsuarios', 'ultimasPublicaciones'));
        }

        if ($user->rol === 'gerente') {
            return view('dashboard.gerente');
        }

        return view('dashboard.index');
    }

    public function plan()
{
    return view('dashboard.plan');
}
}