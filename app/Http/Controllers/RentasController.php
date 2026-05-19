<?php

namespace App\Http\Controllers;
 
use App\Models\PedidoItem;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
 
class RentasController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
 
    /**
     * GET /dashboard/rentas
     * Vista unificada: rentas como arrendador + rentas como cliente.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
 
        // Rentas donde soy ARRENDADOR
        $qArrendador = PedidoItem::with(['pedido.user', 'producto.imagenes'])
            ->where('vendedor_id', $user->id)
            ->where('tipo_accion', 'rentar')
            ->whereHas('pedido', fn($q) => $q->whereIn('estado', ['pagado', 'cancelado']));
 
        if ($request->filled('q')) {
            $qArrendador->where('titulo', 'like', '%' . $request->q . '%');
        }
 
        $rentasPublicadas = $qArrendador->latest()->get();
 
        // Rentas donde soy CLIENTE
        $qCliente = PedidoItem::with(['pedido', 'producto.imagenes', 'producto.user'])
            ->where('tipo_accion', 'rentar')
            ->whereHas('pedido', fn($q) =>
                $q->where('user_id', $user->id)
                  ->whereIn('estado', ['pagado', 'cancelado'])
            );
 
        $rentasCliente = $qCliente->latest()->get();
 
        // Totales para el hero
        $ingresoRentas = $rentasPublicadas
            ->filter(fn($i) => $i->pedido?->estado === 'pagado')
            ->sum('total_item');
 
        return view('dashboard.rentas', compact(
            'rentasPublicadas',
            'rentasCliente',
            'ingresoRentas'
        ));
    }
 
    /**
     * PATCH /dashboard/rentas/{pedidoItem}/reactivar
     * Reactiva la publicación cuando la renta terminó.
     */
    public function reactivar(PedidoItem $pedidoItem)
    {
        $user    = Auth::user();
        $producto = $pedidoItem->producto;
 
        // Solo el arrendador puede reactivar
        if (!$producto || $producto->user_id !== $user->id) {
            abort(403);
        }
 
        // Solo reactivar si está pausado (por renta finalizada)
        if ($producto->estado === 'pausado') {
            $producto->update(['estado' => 'activo']);
        }
 
        return back()->with('success', "\"$producto->titulo\" fue reactivada y ya aparece en búsquedas.");
    }
}