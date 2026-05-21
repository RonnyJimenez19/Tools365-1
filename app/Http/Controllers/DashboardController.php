<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ProductoImagen;
use App\Models\ProductoDetalle;
use App\Models\User;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ── ADMIN ─────────────────────────────────────────────
        if ($user->rol === 'admin') {

            $stats = [
                'usuarios'      => User::count(),
                'publicaciones' => Producto::where('estado', 'activo')->count(),
                'subastas'      => Producto::where('tipo', 'subasta')
                    ->where('estado', 'activo')
                    ->count(),
            ];

            $usuariosRecientes = User::orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $publicacionesRecientes = Producto::with('user')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

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

            $actividad = $actividad
                ->sortByDesc('fecha')
                ->take(8)
                ->values();

            $ultimosUsuarios = User::orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $ultimasPublicaciones = Producto::with('user')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            return view('dashboard.admin', compact(
                'stats',
                'actividad',
                'ultimosUsuarios',
                'ultimasPublicaciones'
            ));
        }

        // ── GERENTE ───────────────────────────────────────────
        if ($user->rol === 'gerente') {
            return view('dashboard.gerente');
        }

        // ── USUARIO NORMAL ────────────────────────────────────

        $publicacionesActivas = Producto::where('user_id', $user->id)
            ->where('estado', 'activo')
            ->count();

        $comprasRealizadas = Pedido::where('user_id', $user->id)
            ->where('estado', 'pagado')
            ->count();

        $subastasActivas = Producto::where('user_id', $user->id)
            ->where('tipo', 'subasta')
            ->where('estado', 'activo')
            ->count();

        // Ingresos del mes en curso
        $ingresosMes = PedidoItem::where('vendedor_id', $user->id)
            ->whereHas('pedido', function ($q) {
                $q->where('estado', 'pagado')
                  ->whereMonth('pagado_at', now()->month)
                  ->whereYear('pagado_at', now()->year);
            })
            ->sum('neto_vendedor');

        return view('dashboard.index', compact(
            'publicacionesActivas',
            'comprasRealizadas',
            'subastasActivas',
            'ingresosMes',
        ));
    }

    public function plan()
    {
        return view('dashboard.plan');
    }
}