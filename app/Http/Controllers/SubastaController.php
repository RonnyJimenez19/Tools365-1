<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\SubastaPuja;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubastaController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ──────────────────────────────────────────────────────
    // GET /subastas
    // Index: subastas en las que participo + las que publiqué
    // ──────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user = Auth::user();

        // Subastas que PUBLIQUÉ (como vendedor)
        $publicadas = Producto::with(['imagenes', 'pujas' => fn($q) => $q->latest()->limit(1)])
            ->where('user_id', $user->id)
            ->where('tipo', 'subasta')
            ->withCount('pujas')
            ->latest()
            ->get()
            ->map(function ($p) {
                $p->puja_actual  = $p->pujas()->max('monto') ?? $p->precio;
                $p->puja_top     = $p->pujas()->with('user')->orderByDesc('monto')->first();
                $p->activa       = $p->estado === 'activo'
                                && $p->timer_fin
                                && Carbon::parse($p->timer_fin)->isFuture();
                return $p;
            });

        // Subastas en las que PARTICIPO (como pujador)
        $participando = Producto::with(['imagenes', 'user'])
            ->whereIn('id',
                SubastaPuja::where('user_id', $user->id)
                    ->pluck('producto_id')
                    ->unique()
            )
            ->where('tipo', 'subasta')
            ->where('user_id', '!=', $user->id)
            ->get()
            ->map(function ($p) use ($user) {
                $p->puja_actual   = $p->pujas()->max('monto') ?? $p->precio;
                $p->mi_mejor_puja = $p->pujas()->where('user_id', $user->id)->max('monto');
                $p->voy_ganando   = $p->pujas()->orderByDesc('monto')->value('user_id') === $user->id;
                $p->activa        = $p->estado === 'activo'
                                 && $p->timer_fin
                                 && Carbon::parse($p->timer_fin)->isFuture();
                return $p;
            });

        return view('subastas.index', compact('publicadas', 'participando'));
    }

    // ──────────────────────────────────────────────────────
    // POST /subastas/{producto}/pujar
    // ──────────────────────────────────────────────────────
    public function pujar(Request $request, Producto $producto)
    {
        $user = Auth::user();

        // Validaciones básicas
        if ($producto->tipo !== 'subasta') {
            return back()->with('error', 'Este producto no es una subasta.');
        }

        if ($producto->estado !== 'activo') {
            return back()->with('error', 'Esta subasta ya no está activa.');
        }

        if ($producto->user_id === $user->id) {
            return back()->with('error', 'No puedes pujar en tu propia subasta.');
        }

        if ($producto->timer_fin && Carbon::parse($producto->timer_fin)->isPast()) {
            return back()->with('error', 'El tiempo de esta subasta ha expirado.');
        }

        // Puja mínima = puja_actual + incremento_minimo
        $pujaActual       = $producto->pujas()->max('monto') ?? $producto->precio;
        $incrementoMinimo = $producto->incremento_minimo ?? 50;
        $minimoRequerido  = $pujaActual + $incrementoMinimo;

        $request->validate([
            'monto' => [
                'required',
                'numeric',
                "min:{$minimoRequerido}",
            ],
        ], [
            'monto.min' => "La puja mínima es $" . number_format($minimoRequerido, 2) . " MXN.",
        ]);

        DB::transaction(function () use ($request, $producto, $user, $pujaActual) {
            // Registrar puja
            SubastaPuja::create([
                'producto_id' => $producto->id,
                'user_id'     => $user->id,
                'monto'       => $request->monto,
            ]);

            // Actualizar precio del producto (puja actual visible)
            $producto->update(['precio' => $request->monto]);

            // Notificar al dueño de la subasta
            Notificacion::create([
                'user_id'  => $producto->user_id,
                'tipo'     => 'nueva_puja',
                'titulo'   => '¡Nueva puja en tu subasta!',
                'cuerpo'   => "\"{$producto->titulo}\" recibió una puja de $" . number_format($request->monto, 2) . " MXN de {$user->name}.",
                'url'      => '/subastas',
                'ref_tipo' => 'producto',
                'ref_id'   => $producto->id,
                'leida'    => false,
            ]);

            // Notificar al pujador anterior (si existe y es diferente al actual)
            $pujadorAnteriorId = SubastaPuja::where('producto_id', $producto->id)
                ->where('user_id', '!=', $user->id)
                ->orderByDesc('monto')
                ->value('user_id');

            if ($pujadorAnteriorId) {
                Notificacion::create([
                    'user_id'  => $pujadorAnteriorId,
                    'tipo'     => 'superado_en_subasta',
                    'titulo'   => 'Te superaron en una subasta',
                    'cuerpo'   => "Alguien hizo una puja mayor en \"{$producto->titulo}\". ¡Contraataca!",
                    'url'      => "/productos/{$producto->id}",
                    'ref_tipo' => 'producto',
                    'ref_id'   => $producto->id,
                    'leida'    => false,
                ]);
            }
        });

        return back()->with('success', '¡Puja registrada exitosamente!');
    }

    // ──────────────────────────────────────────────────────
    // GET /subastas/{producto}/pujas   (JSON — para el historial)
    // ──────────────────────────────────────────────────────
    public function historial(Producto $producto)
    {
        abort_if($producto->tipo !== 'subasta', 404);

        $pujas = SubastaPuja::with('user:id,name')
            ->where('producto_id', $producto->id)
            ->orderByDesc('monto')
            ->take(20)
            ->get()
            ->map(fn($p) => [
                'nombre' => $p->user->name ?? 'Anónimo',
                'monto'  => $p->monto,
                'fecha'  => $p->created_at->locale('es')->diffForHumans(),
            ]);

        return response()->json([
            'pujas'       => $pujas,
            'puja_actual' => $producto->pujas()->max('monto') ?? $producto->precio,
            'total_pujas' => $pujas->count(),
        ]);
    }

    // ──────────────────────────────────────────────────────
    // DELETE /subastas/{producto}   — cancelar subasta (dueño)
    // ──────────────────────────────────────────────────────
    public function cancelar(Producto $producto)
    {
        $user = Auth::user();

        if ($producto->user_id !== $user->id && !$user->esAdmin()) {
            abort(403);
        }

        if ($producto->tipo !== 'subasta') {
            return back()->with('error', 'Este producto no es una subasta.');
        }

        $tienePujas = $producto->pujas()->exists();

        DB::transaction(function () use ($producto, $user, $tienePujas) {
            $producto->update([
                'estado'    => 'pausado',
                'timer_fin' => null,
            ]);

            // Notificar a todos los pujadores
            if ($tienePujas) {
                $pujadores = SubastaPuja::where('producto_id', $producto->id)
                    ->distinct('user_id')
                    ->pluck('user_id');

                foreach ($pujadores as $pujadorId) {
                    if ($pujadorId !== $user->id) {
                        Notificacion::create([
                            'user_id'  => $pujadorId,
                            'tipo'     => 'subasta_cancelada',
                            'titulo'   => 'Subasta cancelada',
                            'cuerpo'   => "La subasta \"{$producto->titulo}\" fue cancelada por el vendedor.",
                            'url'      => '/subastas',
                            'ref_tipo' => 'producto',
                            'ref_id'   => $producto->id,
                            'leida'    => false,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('subastas.index')
            ->with('success', 'Subasta cancelada correctamente.');
    }

    // ──────────────────────────────────────────────────────
    // PATCH /subastas/{producto}/vender   — cerrar y vender al mejor postor
    // ──────────────────────────────────────────────────────
    public function vender(Producto $producto)
    {
        $user = Auth::user();

        if ($producto->user_id !== $user->id && !$user->esAdmin()) {
            abort(403);
        }

        if ($producto->tipo !== 'subasta') {
            return back()->with('error', 'Este producto no es una subasta.');
        }

        $mejorPuja = SubastaPuja::where('producto_id', $producto->id)
            ->orderByDesc('monto')
            ->first();

        if (!$mejorPuja) {
            return back()->with('error', 'No hay pujas registradas. No se puede vender.');
        }

        DB::transaction(function () use ($producto, $mejorPuja, $user) {
            // Marcar puja ganadora
            $mejorPuja->update(['ganadora' => true]);

            // Cambiar estado del producto
            $producto->update([
                'estado'    => 'vendido',
                'timer_fin' => now(),
            ]);

            // Notificar al ganador
            Notificacion::create([
                'user_id'  => $mejorPuja->user_id,
                'tipo'     => 'subasta_ganada',
                'titulo'   => '¡Ganaste la subasta!',
                'cuerpo'   => "¡Felicidades! Ganaste la subasta de \"{$producto->titulo}\" con $" . number_format($mejorPuja->monto, 2) . " MXN. El vendedor se pondrá en contacto contigo.",
                'url'      => "/productos/{$producto->id}",
                'ref_tipo' => 'producto',
                'ref_id'   => $producto->id,
                'leida'    => false,
            ]);

            // Notificar a los demás pujadores
            $otrosPujadores = SubastaPuja::where('producto_id', $producto->id)
                ->where('user_id', '!=', $mejorPuja->user_id)
                ->distinct('user_id')
                ->pluck('user_id');

            foreach ($otrosPujadores as $pujadorId) {
                Notificacion::create([
                    'user_id'  => $pujadorId,
                    'tipo'     => 'subasta_finalizada',
                    'titulo'   => 'Subasta finalizada',
                    'cuerpo'   => "La subasta de \"{$producto->titulo}\" fue adjudicada a otro participante.",
                    'url'      => '/subastas',
                    'ref_tipo' => 'producto',
                    'ref_id'   => $producto->id,
                    'leida'    => false,
                ]);
            }

            // Notificar al vendedor
            Notificacion::create([
                'user_id'  => $user->id,
                'tipo'     => 'venta_realizada',
                'titulo'   => 'Subasta cerrada con éxito',
                'cuerpo'   => "Tu subasta \"{$producto->titulo}\" fue vendida por $" . number_format($mejorPuja->monto, 2) . " MXN.",
                'url'      => '/subastas',
                'ref_tipo' => 'producto',
                'ref_id'   => $producto->id,
                'leida'    => false,
            ]);
        });

        return redirect()->route('subastas.index')
            ->with('success', '¡Subasta cerrada! El ganador ha sido notificado.');
    }
}