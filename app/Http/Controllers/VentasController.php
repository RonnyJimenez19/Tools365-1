<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoItem;
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
     * Vista del VENDEDOR: todas las ventas/rentas de sus productos.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = PedidoItem::with(['pedido.user', 'producto.imagenes'])
            ->where('vendedor_id', $user->id)
            ->whereHas('pedido', fn($q) => $q->where('estado', 'pagado'))
            ->latest();

        // Filtro por tipo
        if ($request->filled('tipo') && in_array($request->tipo, ['comprar', 'rentar'])) {
            $query->where('tipo_accion', $request->tipo);
        }

        // Búsqueda
        if ($request->filled('q')) {
            $query->where('titulo', 'like', '%' . $request->q . '%');
        }

        $ventas = $query->paginate(12)->withQueryString();

        // Totales resumen
        $totalIngresos = PedidoItem::where('vendedor_id', $user->id)
            ->whereHas('pedido', fn($q) => $q->where('estado', 'pagado'))
            ->sum('total_item');

        $totalVentas = PedidoItem::where('vendedor_id', $user->id)
            ->where('tipo_accion', 'comprar')
            ->whereHas('pedido', fn($q) => $q->where('estado', 'pagado'))
            ->count();

        $totalRentas = PedidoItem::where('vendedor_id', $user->id)
            ->where('tipo_accion', 'rentar')
            ->whereHas('pedido', fn($q) => $q->where('estado', 'pagado'))
            ->count();

        return view('dashboard.ventas', compact(
            'ventas', 'totalIngresos', 'totalVentas', 'totalRentas'
        ));
    }

    /**
     * GET /dashboard/compras
     * Vista del COMPRADOR: todos sus pedidos pagados.
     */
    public function compras(Request $request)
    {
        $user = Auth::user();

        $query = Pedido::with(['items.producto.imagenes'])
            ->where('user_id', $user->id)
            ->where('estado', 'pagado')
            ->latest('pagado_at');

        // Filtro por tipo de acción dentro del pedido
        if ($request->filled('tipo')) {
            $query->whereHas('items', fn($q) => $q->where('tipo_accion', $request->tipo));
        }

        $pedidos = $query->paginate(10)->withQueryString();

        $totalGastado = Pedido::where('user_id', $user->id)
            ->where('estado', 'pagado')
            ->sum('total');

        $totalPedidos = Pedido::where('user_id', $user->id)
            ->where('estado', 'pagado')
            ->count();

        return view('dashboard.compras', compact('pedidos', 'totalGastado', 'totalPedidos'));
    }
}