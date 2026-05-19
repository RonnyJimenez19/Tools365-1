<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class VentasController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * GET /dashboard/ventas
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = PedidoItem::with(['pedido.user', 'producto.imagenes'])
            ->where('vendedor_id', $user->id)
            ->whereHas('pedido', fn($q) => $q->where('estado', 'pagado'))
            ->latest();

        if ($request->filled('tipo') && in_array($request->tipo, ['comprar', 'rentar'])) {
            $query->where('tipo_accion', $request->tipo);
        }

        if ($request->filled('q')) {
            $query->where('titulo', 'like', '%' . $request->q . '%');
        }

        $ventas = $query->paginate(12)->withQueryString();

        // ── Resumen financiero global ────────────────────────────────────────
        $base = PedidoItem::where('vendedor_id', $user->id)
            ->whereHas('pedido', fn($q) => $q->where('estado', 'pagado'));

        $totalBruto    = (clone $base)->sum('total_item');
        $totalComision = (clone $base)->sum('comision_plataforma');
        $totalNeto     = (clone $base)->sum('neto_vendedor');
        $totalVentas   = (clone $base)->where('tipo_accion', 'comprar')->count();
        $totalRentas   = (clone $base)->where('tipo_accion', 'rentar')->count();

        // Plan y tasa actual del vendedor
        $planActual   = $user->plan ?? 'free';
        $tasaActual   = PlanService::comisionLabel($planActual);

        return view('dashboard.ventas', compact(
            'ventas',
            'totalBruto',
            'totalComision',
            'totalNeto',
            'totalVentas',
            'totalRentas',
            'planActual',
            'tasaActual'
        ));
    }

    /**
     * GET /dashboard/compras
     */
    public function compras(Request $request)
    {
        $user = Auth::user();

        $query = Pedido::with(['items.producto.imagenes'])
            ->where('user_id', $user->id)
            ->where('estado', 'pagado')
            ->latest('pagado_at');

        if ($request->filled('tipo')) {
            $query->whereHas('items', fn($q) => $q->where('tipo_accion', $request->tipo));
        }

        $pedidos      = $query->paginate(10)->withQueryString();
        $totalGastado = Pedido::where('user_id', $user->id)->where('estado', 'pagado')->sum('total');
        $totalPedidos = Pedido::where('user_id', $user->id)->where('estado', 'pagado')->count();

        return view('dashboard.compras', compact('pedidos', 'totalGastado', 'totalPedidos'));
    }
}