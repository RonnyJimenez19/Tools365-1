<?php

namespace App\Http\Controllers;

use App\Models\CarritoItem;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CarritoController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── Vista del carrito ────────────────────────────────────────────────────

    public function index()
    {
        $items = CarritoItem::with(['producto.imagenes', 'producto.categoria'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $subtotal = $items->sum('total_calculado');

        return view('carrito.index', compact('items', 'subtotal'));
    }

    // ── Agregar o actualizar ítem ────────────────────────────────────────────

    public function store(Request $request)
    {
        $producto = Producto::findOrFail($request->producto_id);

        // Solo productos activos
        if ($producto->estado !== 'activo') {
            return $this->responder($request, 'error', 'Este producto no está disponible.');
        }

        // Determinar tipo_accion según el tipo de producto
        $tipoAccion = ($producto->tipo === 'renta') ? 'rentar' : 'comprar';

        // Validación diferenciada
        if ($tipoAccion === 'rentar') {
            $request->validate([
                'fecha_inicio' => 'required|date|after_or_equal:today',
                'fecha_fin'    => 'required|date|after:fecha_inicio',
            ], [
                'fecha_inicio.required'      => 'La fecha de inicio es obligatoria.',
                'fecha_inicio.after_or_equal'=> 'La fecha de inicio no puede ser en el pasado.',
                'fecha_fin.required'         => 'La fecha de fin es obligatoria.',
                'fecha_fin.after'            => 'La fecha de fin debe ser posterior a la de inicio.',
            ]);
        } else {
            $request->validate([
                'cantidad' => 'nullable|integer|min:1|max:99',
            ]);
        }

        // Buscar ítem existente (mismo usuario + producto + acción)
        $item = CarritoItem::firstOrNew([
            'user_id'     => Auth::id(),
            'producto_id' => $producto->id,
            'tipo_accion' => $tipoAccion,
        ]);

        $item->precio_unitario = $producto->precio;
        $item->cantidad        = ($tipoAccion === 'comprar') ? max(1, (int) $request->get('cantidad', 1)) : 1;
        $item->fecha_inicio    = ($tipoAccion === 'rentar') ? $request->fecha_inicio : null;
        $item->fecha_fin       = ($tipoAccion === 'rentar') ? $request->fecha_fin    : null;
        $item->recalcularTotal();
        $item->save();

        $conteo = CarritoItem::where('user_id', Auth::id())->count();

        return $this->responder(
            $request,
            'success',
            '¡Agregado al carrito!',
            ['conteo' => $conteo]
        );
    }

    // ── Actualizar cantidad / fechas ─────────────────────────────────────────

    public function update(Request $request, CarritoItem $carritoItem)
    {
        $this->autorizarItem($carritoItem);

        if ($carritoItem->tipo_accion === 'rentar') {
            $request->validate([
                'fecha_inicio' => 'required|date|after_or_equal:today',
                'fecha_fin'    => 'required|date|after:fecha_inicio',
            ]);
            $carritoItem->fecha_inicio = $request->fecha_inicio;
            $carritoItem->fecha_fin    = $request->fecha_fin;
        } else {
            $request->validate(['cantidad' => 'required|integer|min:1|max:99']);
            $carritoItem->cantidad = $request->cantidad;
        }

        $carritoItem->recalcularTotal();
        $carritoItem->save();

        $subtotal = CarritoItem::where('user_id', Auth::id())->sum('total_calculado');

        return $this->responder($request, 'success', 'Carrito actualizado.', [
            'total_item' => number_format($carritoItem->total_calculado, 2),
            'subtotal'   => number_format($subtotal, 2),
        ]);
    }

    // ── Eliminar ítem ────────────────────────────────────────────────────────

    public function destroy(CarritoItem $carritoItem)
    {
        $this->autorizarItem($carritoItem);
        $carritoItem->delete();

        $conteo   = CarritoItem::where('user_id', Auth::id())->count();
        $subtotal = CarritoItem::where('user_id', Auth::id())->sum('total_calculado');

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'ok'       => true,
                'conteo'   => $conteo,
                'subtotal' => number_format($subtotal, 2),
            ]);
        }

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    // ── Vaciar carrito ───────────────────────────────────────────────────────

    public function vaciar()
    {
        CarritoItem::where('user_id', Auth::id())->delete();
        return back()->with('success', 'Carrito vaciado.');
    }

    // ── Checkout (resumen antes de "confirmar pedido") ───────────────────────

    public function checkout()
    {
        $items = CarritoItem::with(['producto.imagenes', 'producto.categoria'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('carrito.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        // Detectar productos que ya no están activos
        $inactivos = $items->filter(
            fn($i) => $i->producto->estado !== 'activo'
        );

        $subtotal = $items->sum('total_calculado');

        return view('carrito.checkout', compact('items', 'subtotal', 'inactivos'));
    }

    // ── Contador (JSON) para actualizar el badge del navbar ─────────────────

    public function conteo()
    {
        $conteo = Auth::check()
            ? CarritoItem::where('user_id', Auth::id())->count()
            : 0;

        return response()->json(['conteo' => $conteo]);
    }

    // ── Helpers privados ─────────────────────────────────────────────────────

    private function autorizarItem(CarritoItem $item): void
    {
        if ($item->user_id !== Auth::id()) {
            abort(403);
        }
    }

    private function responder(Request $request, string $tipo, string $mensaje, array $extra = [])
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(array_merge([
                'ok'      => $tipo === 'success',
                'mensaje' => $mensaje,
            ], $extra));
        }

        return back()->with($tipo, $mensaje);
    }
}