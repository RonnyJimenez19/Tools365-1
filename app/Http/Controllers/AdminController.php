<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Producto;
use App\Models\Comentario;
use App\Models\PedidoItem;
use App\Models\Pedido;
use App\Models\SubastaPuja;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        if ($user->id === auth()->id() && $request->filled('status') && $request->input('status') !== 'activo') {
            return back()->with('error', 'No puedes cambiar tu propio estado de cuenta.');
        }

        $validated = $request->validate([
            'rol'    => 'sometimes|in:admin,gerente,invitado',
            'plan'   => 'sometimes|in:free,basico,profesional',
            'status' => 'sometimes|in:activo,bloqueado,inactivo',
        ]);

        $user->update($validated);

        return back()->with('success', "Usuario {$user->name} actualizado correctamente.");
    }

    // ── Toggle bloqueo rápido ─────────────────────────────────────────────────
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

        $stats = [
            'total'    => Producto::count(),
            'activos'  => Producto::where('estado', 'activo')->count(),
            'pausados' => Producto::where('estado', 'pausado')->count(),
            'vendidos' => Producto::where('estado', 'vendido')->count(),
        ];

        return view('dashboard.admin.publicaciones', compact('publicaciones', 'stats'));
    }

    public function updatePublicacion(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'estado' => 'required|in:activo,pausado,vendido,eliminado',
        ]);

        $producto->update($validated);

        return back()->with('success', "Publicación \"{$producto->titulo}\" actualizada correctamente.");
    }

    public function destroyPublicacion(Producto $producto)
    {
        $titulo = $producto->titulo;
        $producto->delete();

        return back()->with('success', "Publicación \"{$titulo}\" eliminada correctamente.");
    }

    // ── Ingresos ─────────────────────────────────────────────────────────────
    public function ingresos(Request $request)
    {
        $periodo = $request->get('periodo', '30');
        $desde   = now()->subDays((int) $periodo)->startOfDay();

        $baseItems = PedidoItem::whereHas('pedido', fn($q) =>
            $q->where('estado', 'pagado')->where('pagado_at', '>=', $desde)
        );

        $totalBruto    = (clone $baseItems)->sum('total_item');
        $totalComision = (clone $baseItems)->sum('comision_plataforma');
        $totalNeto     = (clone $baseItems)->sum('neto_vendedor');
        $totalTx       = (clone $baseItems)->count();

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

        $porPlan = PedidoItem::selectRaw("
                u.plan,
                COUNT(pi.id)                     AS cantidad,
                SUM(pi.total_item)               AS bruto,
                SUM(pi.comision_plataforma)      AS comision,
                SUM(pi.neto_vendedor)            AS neto
            ")
            ->from('pedido_items AS pi')
            ->join('users AS u', 'u.id', '=', 'pi.vendedor_id')
            ->join('pedidos AS p', 'p.id', '=', 'pi.pedido_id')
            ->where('p.estado', 'pagado')
            ->where('p.pagado_at', '>=', $desde)
            ->groupBy('u.plan')
            ->get();

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

        $ultimasTx = PedidoItem::with(['pedido', 'vendedor:id,name,plan'])
            ->whereHas('pedido', fn($q) =>
                $q->where('estado', 'pagado')->where('pagado_at', '>=', $desde)
            )
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

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

    // ══════════════════════════════════════════════════════════════════════════
    // NUEVO: Opiniones / Comentarios
    // ══════════════════════════════════════════════════════════════════════════

    public function opiniones(Request $request)
    {
        $query = Comentario::with('user')->latest();

        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        if ($inicio = $request->input('en_inicio')) {
            $query->where('en_inicio', $inicio === '1');
        }

        if ($search = $request->input('buscar')) {
            $query->where(function ($q) use ($search) {
                $q->where('autor_nombre', 'like', "%{$search}%")
                  ->orWhere('cuerpo', 'like', "%{$search}%");
            });
        }

        $comentarios = $query->paginate(12)->withQueryString();

        $stats = [
            'total'     => Comentario::count(),
            'aprobados' => Comentario::where('estado', 'aprobado')->count(),
            'pendientes'=> Comentario::where('estado', 'pendiente')->count(),
            'en_inicio' => Comentario::where('en_inicio', true)->where('estado', 'aprobado')->count(),
        ];

        return view('dashboard.admin.opiniones', compact('comentarios', 'stats'));
    }

    public function updateOpinion(Request $request, Comentario $comentario)
    {
        $request->validate([
            'estado' => 'required|in:aprobado,pendiente,rechazado',
        ]);

        $comentario->update(['estado' => $request->estado]);

        $label = match($request->estado) {
            'aprobado'  => 'aprobado',
            'pendiente' => 'marcado como pendiente',
            'rechazado' => 'rechazado',
        };

        return back()->with('success', "Comentario de \"{$comentario->autor_nombre}\" {$label}.");
    }

    public function toggleInicio(Comentario $comentario)
    {
        if ($comentario->estado !== 'aprobado') {
            return back()->with('error', 'Solo los comentarios aprobados pueden mostrarse en inicio.');
        }

        $nuevoValor = !$comentario->en_inicio;
        $comentario->update(['en_inicio' => $nuevoValor]);

        $accion = $nuevoValor ? 'añadido a la página de inicio' : 'removido de la página de inicio';

        return back()->with('success', "Comentario de \"{$comentario->autor_nombre}\" {$accion}.");
    }

    public function destroyOpinion(Comentario $comentario)
    {
        $autor = $comentario->autor_nombre;
        $comentario->delete();

        return back()->with('success', "Comentario de \"{$autor}\" eliminado correctamente.");
    }

    // ══════════════════════════════════════════════════════════════════════════
    // NUEVO: Todas las herramientas
    // ══════════════════════════════════════════════════════════════════════════

    public function herramientas(Request $request)
    {
        $query = Producto::with(['user', 'categoria', 'imagenes' => fn($q) => $q->orderBy('orden')->limit(1)])
            ->whereNotIn('tipo', ['subasta']); // subastas tienen su propia sección

        if ($search = $request->input('buscar')) {
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('ubicacion', 'like', "%{$search}%");
            });
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

        if ($categoria = $request->input('categoria_id')) {
            $query->where('categoria_id', $categoria);
        }

        $herramientas = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        $stats = [
            'total'    => Producto::whereNotIn('tipo', ['subasta'])->count(),
            'activos'  => Producto::whereNotIn('tipo', ['subasta'])->where('estado', 'activo')->count(),
            'pausados' => Producto::whereNotIn('tipo', ['subasta'])->where('estado', 'pausado')->count(),
            'vendidos' => Producto::whereNotIn('tipo', ['subasta'])->where('estado', 'vendido')->count(),
            'rentas'   => Producto::where('tipo', 'renta')->where('estado', 'activo')->count(),
            'ventas'   => Producto::where('tipo', 'venta')->where('estado', 'activo')->count(),
        ];

        $categorias = \App\Models\Categoria::where('estado', 'activo')->orderBy('nombre')->get();
        $usuarios   = User::orderBy('name')->get(['id', 'name']);

        return view('dashboard.admin.herramientas', compact('herramientas', 'stats', 'categorias', 'usuarios'));
    }

    public function updateHerramienta(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'estado' => 'required|in:activo,pausado,vendido,eliminado',
        ]);

        $producto->update($validated);

        return back()->with('success', "Herramienta \"{$producto->titulo}\" actualizada a '{$validated['estado']}'.");
    }

    public function destroyHerramienta(Producto $producto)
    {
        $titulo = $producto->titulo;
        $producto->delete();

        return back()->with('success', "Herramienta \"{$titulo}\" eliminada permanentemente.");
    }

    // ══════════════════════════════════════════════════════════════════════════
    // NUEVO: Subastas activas
    // ══════════════════════════════════════════════════════════════════════════

    public function subastas(Request $request)
    {
        $query = Producto::with([
                'user',
                'categoria',
                'imagenes'  => fn($q) => $q->orderBy('orden')->limit(1),
                'pujas'     => fn($q) => $q->orderByDesc('monto')->limit(1)->with('user:id,name'),
            ])
            ->where('tipo', 'subasta')
            ->withCount('pujas');

        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        } else {
            // Por defecto solo activas y adjudicadas
            $query->whereIn('estado', ['activo', 'adjudicado', 'adjudicado_expirado']);
        }

        if ($search = $request->input('buscar')) {
            $query->where('titulo', 'like', "%{$search}%");
        }

        $subastas = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        // Enriquecer con datos calculados
        $subastas->getCollection()->transform(function ($p) {
            $p->puja_actual  = $p->pujas()->max('monto') ?? $p->precio;
            $p->puja_top     = $p->pujas()->with('user:id,name')->orderByDesc('monto')->first();
            $p->esta_activa  = $p->estado === 'activo'
                            && $p->timer_fin
                            && \Carbon\Carbon::parse($p->timer_fin)->isFuture();
            return $p;
        });

        $stats = [
            'total'          => Producto::where('tipo', 'subasta')->count(),
            'activas'        => Producto::where('tipo', 'subasta')->where('estado', 'activo')->count(),
            'adjudicadas'    => Producto::where('tipo', 'subasta')->where('estado', 'adjudicado')->count(),
            'vendidas'       => Producto::where('tipo', 'subasta')->where('estado', 'vendido')->count(),
            'canceladas'     => Producto::where('tipo', 'subasta')->where('estado', 'pausado')->count(),
            'total_pujas'    => SubastaPuja::count(),
        ];

        return view('dashboard.admin.subastas', compact('subastas', 'stats'));
    }

    public function cancelarSubasta(Producto $producto)
    {
        if ($producto->tipo !== 'subasta') {
            return back()->with('error', 'Este producto no es una subasta.');
        }

        DB::transaction(function () use ($producto) {
            $producto->update(['estado' => 'pausado', 'timer_fin' => null]);

            $pujadores = SubastaPuja::where('producto_id', $producto->id)
                ->distinct('user_id')->pluck('user_id');

            foreach ($pujadores as $pujadorId) {
                Notificacion::create([
                    'user_id'  => $pujadorId,
                    'tipo'     => 'subasta_cancelada',
                    'titulo'   => 'Subasta cancelada por administración',
                    'cuerpo'   => "La subasta \"{$producto->titulo}\" fue cancelada por un administrador.",
                    'url'      => '/subastas',
                    'ref_tipo' => 'producto',
                    'ref_id'   => $producto->id,
                    'leida'    => false,
                ]);
            }

            // Notificar al vendedor
            Notificacion::create([
                'user_id'  => $producto->user_id,
                'tipo'     => 'subasta_cancelada',
                'titulo'   => 'Tu subasta fue cancelada por administración',
                'cuerpo'   => "La subasta \"{$producto->titulo}\" fue cancelada por un administrador de la plataforma.",
                'url'      => '/subastas',
                'ref_tipo' => 'producto',
                'ref_id'   => $producto->id,
                'leida'    => false,
            ]);
        });

        return back()->with('success', "Subasta \"{$producto->titulo}\" cancelada y participantes notificados.");
    }

    // ══════════════════════════════════════════════════════════════════════════
    // NUEVO: Rentas activas
    // ══════════════════════════════════════════════════════════════════════════

    public function rentas(Request $request)
    {
        $query = PedidoItem::with(['pedido.user', 'producto.imagenes', 'producto.user', 'vendedor:id,name,plan'])
            ->where('tipo_accion', 'rentar')
            ->whereHas('pedido', fn($q) => $q->whereIn('estado', ['pagado', 'cancelado']));

        if ($estado = $request->input('estado')) {
            $query->whereHas('pedido', fn($q) => $q->where('estado', $estado));
        }

        if ($search = $request->input('buscar')) {
            $query->where('titulo', 'like', "%{$search}%");
        }

        $rentas = $query->orderByDesc('created_at')->paginate(12)->withQueryString();

        $stats = [
            'total'         => PedidoItem::where('tipo_accion', 'rentar')->count(),
            'activas'       => PedidoItem::where('tipo_accion', 'rentar')
                                ->whereHas('pedido', fn($q) => $q->where('estado', 'pagado'))
                                ->whereRaw('fecha_fin >= CURDATE()')
                                ->count(),
            'finalizadas'   => PedidoItem::where('tipo_accion', 'rentar')
                                ->whereHas('pedido', fn($q) => $q->where('estado', 'pagado'))
                                ->whereRaw('fecha_fin < CURDATE()')
                                ->count(),
            'ingresos'      => PedidoItem::where('tipo_accion', 'rentar')
                                ->whereHas('pedido', fn($q) => $q->where('estado', 'pagado'))
                                ->sum('total_item'),
            'comisiones'    => PedidoItem::where('tipo_accion', 'rentar')
                                ->whereHas('pedido', fn($q) => $q->where('estado', 'pagado'))
                                ->sum('comision_plataforma'),
        ];

        return view('dashboard.admin.rentas', compact('rentas', 'stats'));
    }

    // ── Actividad reciente (para widget en dashboard admin) ──────────────────
    public function actividadReciente()
    {
        $usuarios      = User::orderBy('created_at', 'desc')->take(5)->get();
        $publicaciones = Producto::with('user')->orderBy('created_at', 'desc')->take(5)->get();

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
}