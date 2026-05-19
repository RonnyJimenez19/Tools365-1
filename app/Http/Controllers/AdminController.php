<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Producto;
use Illuminate\Http\Request;;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PedidoItem;
use App\Models\Pedido;


class AdminController extends Controller
{
    // ── Listado de usuarios ──────────────────────────────────────────────────
    public function usuarios(Request $request)
    {
        $query = User::query();

        if ($search = $request->input('buscar')) {
            $query->where(function ($q) use ($search) {
                $q->where('name',  'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($rol = $request->input('rol')) {
            $query->where('rol', $rol);
        }

        if ($plan = $request->input('plan')) {
            $query->where('plan', $plan);
        }

        if ($estado = $request->input('estado')) {
            $query->where('status', $estado);
        }

        $usuarios = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('dashboard.admin.usuarios', compact('usuarios'));
    }

    // ── Actualizar rol, plan o status (modal) ────────────────────────────────
    public function updateUsuario(Request $request, User $user)
    {
        if ($user->id === auth()->id() && $request->filled('rol') && $request->input('rol') !== 'admin') {
            return back()->with('error', 'No puedes quitarte el rol de administrador.');
        }

        // Evitar que el admin se bloquee/inactive a sí mismo
        if ($user->id === auth()->id() && $request->filled('status') && $request->input('status') !== 'activo') {
            return back()->with('error', 'No puedes cambiar tu propio estado de cuenta.');
        }

        $validated = $request->validate([
            'rol'    => 'sometimes|in:admin,gerente,invitado',
            'plan' => 'sometimes|in:free,basico,profesional',
            'status' => 'sometimes|in:activo,bloqueado,inactivo',
        ]);

        $user->update($validated);

        return back()->with('success', "Usuario {$user->name} actualizado correctamente.");
    }

    // ── Toggle bloqueo rápido (botón directo) ────────────────────────────────
    public function toggleBloqueo(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes bloquearte a ti mismo.');
        }

        $nuevoStatus = $user->status === 'bloqueado' ? 'activo' : 'bloqueado';
        $user->update(['status' => $nuevoStatus]);

        return back()->with('success', "Usuario {$user->name} {$nuevoStatus} correctamente.");
    }

    // ── Listado de publicaciones ─────────────────────────────────────────────
    public function publicaciones(Request $request)
    {
        $query = Producto::with(['user', 'imagenes' => fn($q) => $q->orderBy('orden')->limit(1)]);

        if ($search = $request->input('buscar')) {
            $query->where('titulo', 'like', "%{$search}%");
        }

        if ($tipo = $request->input('tipo')) {
            $query->where('tipo', $tipo);
        }

        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        if ($usuario = $request->input('usuario_id')) {
            $query->where('user_id', $usuario);
        }

        $publicaciones = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Stats rápidos
        $stats = [
            'total'    => Producto::count(),
            'activos'  => Producto::where('estado', 'activo')->count(),
            'pausados' => Producto::where('estado', 'pausado')->count(),
            'vendidos' => Producto::where('estado', 'vendido')->count(),
        ];

        return view('dashboard.admin.publicaciones', compact('publicaciones', 'stats'));
    }

    // ── Actualizar estado de publicación ────────────────────────────────────
    public function updatePublicacion(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'estado' => 'required|in:activo,pausado,vendido,eliminado',
        ]);

        $producto->update($validated);

        return back()->with('success', "Publicación \"{$producto->titulo}\" actualizada correctamente.");
    }

    // ── Eliminar publicación ─────────────────────────────────────────────────
    public function destroyPublicacion(Producto $producto)
    {
        $titulo = $producto->titulo;
        $producto->delete();

        return back()->with('success', "Publicación \"{$titulo}\" eliminada correctamente.");
    }

    // ── Actividad reciente (para widget en dashboard admin) ──────────────────
    public function actividadReciente()
    {
        $usuarios    = User::orderBy('created_at', 'desc')->take(5)->get();
        $publicaciones = Producto::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        // Mezclar y ordenar por fecha
        $actividad = collect();

        foreach ($usuarios as $u) {
            $actividad->push([
                'tipo'  => 'usuario',
                'icono' => 'bi-person-check-fill',
                'color' => 'green',
                'msg'   => "<strong>{$u->name}</strong> se registró en la plataforma",
                'fecha' => $u->created_at,
            ]);
        }

        foreach ($publicaciones as $p) {
            $actividad->push([
                'tipo'  => 'publicacion',
                'icono' => 'bi-box-seam-fill',
                'color' => 'blue',
                'msg'   => "<strong>{$p->user?->name}</strong> publicó \"{$p->titulo}\"",
                'fecha' => $p->created_at,
            ]);
        }

        return $actividad->sortByDesc('fecha')->take(8)->values();
    }

    public function ingresos(Request $request)
{
    // ── Filtros ────────────────────────────────────────────────────────────
    $periodo = $request->get('periodo', '30'); // días
    $desde   = now()->subDays((int) $periodo)->startOfDay();
 
    $baseItems = PedidoItem::whereHas('pedido', fn($q) =>
        $q->where('estado', 'pagado')->where('pagado_at', '>=', $desde)
    );
 
    // ── Totales globales ───────────────────────────────────────────────────
    $totalBruto    = (clone $baseItems)->sum('total_item');
    $totalComision = (clone $baseItems)->sum('comision_plataforma');
    $totalNeto     = (clone $baseItems)->sum('neto_vendedor');
    $totalTx       = (clone $baseItems)->count();
 
    // Por tipo
    $porTipo = PedidoItem::selectRaw("
            tipo_accion,
            COUNT(*)                         AS cantidad,
            SUM(total_item)                  AS bruto,
            SUM(comision_plataforma)         AS comision,
            SUM(neto_vendedor)               AS neto
        ")
        ->whereHas('pedido', fn($q) =>
            $q->where('estado', 'pagado')->where('pagado_at', '>=', $desde)
        )
        ->groupBy('tipo_accion')
        ->get();
 
    // Por plan del vendedor
    $porPlan = PedidoItem::selectRaw("
            u.plan,
            COUNT(pi.id)                     AS cantidad,
            SUM(pi.total_item)               AS bruto,
            SUM(pi.comision_plataforma)      AS comision,
            SUM(pi.neto_vendedor)            AS neto
        ")
        ->from('pedido_items AS pi')
        ->join('users AS u', 'u.id', '=', 'pi.vendedor_id')
        ->whereHas('pedido', fn($q) =>
            $q->where('estado', 'pagado')->where('pagado_at', '>=', $desde)
        )
        ->groupBy('u.plan')
        ->get();
 
    // Top vendedores por comisión generada
    $topVendedores = PedidoItem::selectRaw("
            vendedor_id,
            SUM(total_item)              AS bruto,
            SUM(comision_plataforma)     AS comision,
            SUM(neto_vendedor)           AS neto,
            COUNT(*)                     AS ventas
        ")
        ->with('vendedor:id,name,email,plan')
        ->whereHas('pedido', fn($q) =>
            $q->where('estado', 'pagado')->where('pagado_at', '>=', $desde)
        )
        ->groupBy('vendedor_id')
        ->orderByDesc('comision')
        ->limit(10)
        ->get();
 
    // Últimas transacciones
    $ultimasTx = PedidoItem::with(['pedido', 'vendedor:id,name,plan'])
        ->whereHas('pedido', fn($q) =>
            $q->where('estado', 'pagado')->where('pagado_at', '>=', $desde)
        )
        ->orderByDesc('created_at')
        ->limit(20)
        ->get();
 
    // Ingresos por suscripciones de plan (pedidos con folio PLAN-)
    $ingresosSubs = Pedido::where('folio', 'like', 'PLAN-%')
        ->where('estado', 'pagado')
        ->where('pagado_at', '>=', $desde)
        ->sum('total');
 
    $cantidadSubs = Pedido::where('folio', 'like', 'PLAN-%')
        ->where('estado', 'pagado')
        ->where('pagado_at', '>=', $desde)
        ->count();
 
    return view('dashboard.admin.ingresos', compact(
        'totalBruto', 'totalComision', 'totalNeto', 'totalTx',
        'porTipo', 'porPlan', 'topVendedores', 'ultimasTx',
        'ingresosSubs', 'cantidadSubs', 'periodo'
    ));
}
}