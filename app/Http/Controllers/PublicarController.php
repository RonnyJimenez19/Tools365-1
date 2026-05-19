<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ProductoImagen;
use App\Models\ProductoDetalle;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PublicarController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── Mis publicaciones ───────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user   = Auth::user();
        $config = PlanService::deUsuario($user);

        $query = Producto::where('user_id', $user->id)
            ->with(['categoria', 'imagenes'])
            ->latest();

        if ($request->filled('estado') && $request->estado !== 'todos') {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('tipo') && $request->tipo !== 'todos') {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('q')) {
            $query->where('titulo', 'like', '%' . $request->q . '%');
        }

        $productos = $query->paginate(10)->withQueryString();

        $conteos = [
            'todos'     => Producto::where('user_id', $user->id)->count(),
            'activo'    => Producto::where('user_id', $user->id)->where('estado', 'activo')->count(),
            'pausado'   => Producto::where('user_id', $user->id)->where('estado', 'pausado')->count(),
            'vendido'   => Producto::where('user_id', $user->id)->where('estado', 'vendido')->count(),
            'eliminado' => Producto::where('user_id', $user->id)->where('estado', 'eliminado')->count(),
        ];

        // Info de límite para el banner de advertencia
        $limiteInfo = [
            'limite'    => $config['publicaciones'],
            'actuales'  => $conteos['activo'],
            'pct'       => PlanService::pctPublicaciones($user),
            'plan'      => $user->plan ?? 'free',
            'label'     => $config['label'],
            'ilimitado' => $config['publicaciones'] === PHP_INT_MAX,
        ];

        return view('publicar.index', compact('productos', 'conteos', 'limiteInfo'));
    }

    // ── Formulario de publicación ───────────────────────────────────────────────
    public function create()
    {
        $user   = Auth::user();
        $config = PlanService::deUsuario($user);

        // ¿Ya alcanzó el límite?
        if (!PlanService::puedePublicar($user)) {
            return redirect()->route('mis-publicaciones.index')
                ->with('plan_limite', $this->mensajeLimite($user, $config));
        }

        $categorias = Categoria::where('estado', 'activo')->orderBy('nombre')->get();

        // Pasar límite de fotos para mostrarlo en el formulario
        $maxFotos = $config['fotos'];

        return view('publicar.create', compact('categorias', 'maxFotos'));
    }

    // ── Guardar nueva publicación ───────────────────────────────────────────────
    public function store(Request $request)
    {
        $user   = Auth::user();
        $config = PlanService::deUsuario($user);

        // Validar límite de plan antes de proceder
        if (!PlanService::puedePublicar($user)) {
            return redirect()->route('mis-publicaciones.index')
                ->with('plan_limite', $this->mensajeLimite($user, $config));
        }

        $maxFotos = $config['fotos'];

        $data = $request->validate([
            'titulo'           => 'required|string|max:200',
            'descripcion'      => 'required|string|min:20|max:2000',
            'precio'           => 'required|numeric|min:0',
            'unidad'           => 'required|string|max:50',
            'ubicacion'        => 'required|string|max:150',
            'tipo'             => 'required|in:renta,venta,subasta',
            'categoria_id'     => 'required|exists:categorias,id',
            'timer_fin'        => 'nullable|date|after:now',
            'imagenes'         => "required|array|min:1|max:{$maxFotos}",
            'imagenes.*'       => 'image|mimes:jpeg,jpg,png,webp|max:4096',
            'detalles'         => 'required|array|min:2',
            'detalles.*.clave' => 'required|string|max:80',
            'detalles.*.valor' => 'required|string|max:150',
        ], $this->mensajesValidacion($maxFotos));

        $producto = Producto::create([
            'user_id'      => Auth::id(),
            'titulo'       => $data['titulo'],
            'descripcion'  => $data['descripcion'] ?? null,
            'precio'       => $data['precio'],
            'unidad'       => $data['unidad'] ?? null,
            'ubicacion'    => $data['ubicacion'] ?? null,
            'tipo'         => $data['tipo'],
            'categoria_id' => $data['categoria_id'],
            'timer_fin'    => $data['timer_fin'] ?? null,
            'estado'       => 'activo',
        ]);

        $this->guardarImagenes($request, $producto);
        $this->guardarDetalles($data, $producto);

        return redirect()->route('mis-publicaciones.index')
            ->with('success', '¡Tu herramienta fue publicada exitosamente!');
    }

    // ── Formulario de edición ───────────────────────────────────────────────────
    public function edit(Producto $producto)
    {
        $this->autorizarPropietario($producto);

        $user     = Auth::user();
        $config   = PlanService::deUsuario($user);
        $maxFotos = $config['fotos'];

        $categorias = Categoria::where('estado', 'activo')->orderBy('nombre')->get();
        $producto->load([
            'imagenes' => fn($q) => $q->orderBy('orden'),
            'detalles' => fn($q) => $q->orderBy('orden'),
        ]);

        return view('publicar.edit', compact('producto', 'categorias', 'maxFotos'));
    }

    // ── Actualizar publicación ──────────────────────────────────────────────────
    public function update(Request $request, Producto $producto)
    {
        $this->autorizarPropietario($producto);

        $user     = Auth::user();
        $config   = PlanService::deUsuario($user);
        $maxFotos = $config['fotos'];

        $data = $request->validate([
            'titulo'           => 'required|string|max:200',
            'descripcion'      => 'required|string|min:20|max:2000',
            'precio'           => 'required|numeric|min:0',
            'unidad'           => 'required|string|max:50',
            'ubicacion'        => 'required|string|max:150',
            'tipo'             => 'required|in:renta,venta,subasta',
            'categoria_id'     => 'required|exists:categorias,id',
            'timer_fin'        => 'nullable|date|after:now',
            'imagenes'         => "nullable|array|max:{$maxFotos}",
            'imagenes.*'       => 'image|mimes:jpeg,jpg,png,webp|max:4096',
            'detalles'         => 'nullable|array',
            'detalles.*.clave' => 'required_with:detalles|string|max:80',
            'detalles.*.valor' => 'required_with:detalles|string|max:150',
            'eliminar_imagenes'   => 'nullable|array',
            'eliminar_imagenes.*' => 'integer|exists:producto_imagenes,id',
        ], $this->mensajesValidacion($maxFotos));

        $producto->update([
            'titulo'       => $data['titulo'],
            'descripcion'  => $data['descripcion'],
            'precio'       => $data['precio'],
            'unidad'       => $data['unidad'],
            'ubicacion'    => $data['ubicacion'],
            'tipo'         => $data['tipo'],
            'categoria_id' => $data['categoria_id'],
            'timer_fin'    => $data['timer_fin'] ?? null,
        ]);

        if (!empty($data['eliminar_imagenes'])) {
            foreach ($data['eliminar_imagenes'] as $imgId) {
                $img = ProductoImagen::find($imgId);
                if ($img && $img->producto_id === $producto->id) {
                    $rutaFisica = public_path($img->ruta);
                    if (file_exists($rutaFisica)) @unlink($rutaFisica);
                    $img->delete();
                }
            }
        }

        // Verificar que el total de fotos no exceda el máximo del plan
        $fotosActuales = $producto->imagenes()->count();
        $nuevasFotos   = $request->hasFile('imagenes') ? count($request->file('imagenes')) : 0;

        if ($fotosActuales + $nuevasFotos > $maxFotos) {
            $permitidas = max(0, $maxFotos - $fotosActuales);
            return back()->with('error',
                "Tu plan {$config['label']} permite máximo {$maxFotos} fotos por publicación. "
                . "Solo puedes agregar {$permitidas} foto(s) más."
            );
        }

        if ($request->hasFile('imagenes')) {
            $ordenActual = $producto->imagenes()->max('orden') + 1;
            $this->guardarImagenes($request, $producto, $ordenActual);
        }

        if (!empty($data['detalles'])) {
            $producto->detalles()->delete();
            $this->guardarDetalles($data, $producto);
        }

        return redirect()->route('mis-publicaciones.index')
            ->with('success', 'Publicación actualizada correctamente.');
    }

    // ── Cambiar estado ─────────────────────────────────────────────────────────
    public function cambiarEstado(Request $request, Producto $producto)
    {
        $this->autorizarPropietario($producto);
        $user = Auth::user();

        $request->validate([
            'estado' => 'required|in:activo,pausado,vendido',
        ]);

        // Si quiere reactivar, verificar límite del plan
        if ($request->estado === 'activo' && $producto->estado !== 'activo') {
            if (!PlanService::puedePublicar($user)) {
                $config = PlanService::deUsuario($user);
                return back()->with('plan_limite', $this->mensajeLimite($user, $config));
            }
        }

        $producto->update(['estado' => $request->estado]);

        $mensajes = [
            'activo'  => 'Publicación reactivada.',
            'pausado' => 'Publicación pausada.',
            'vendido' => 'Publicación marcada como vendida.',
        ];

        return back()->with('success', $mensajes[$request->estado]);
    }

    // ── Eliminar publicación ────────────────────────────────────────────────────
    public function destroy(Producto $producto)
    {
        $this->autorizarPropietario($producto);
        $producto->update(['estado' => 'eliminado']);

        return redirect()->route('mis-publicaciones.index')
            ->with('success', 'Publicación eliminada.');
    }

    // ── Vista de detalle ────────────────────────────────────────────────────────
    public function show(Producto $producto)
    {
        $this->autorizarPropietario($producto);
        $producto->load([
            'imagenes' => fn($q) => $q->orderBy('orden'),
            'detalles',
            'categoria',
            'user',
        ]);

        $relacionados = Producto::with(['imagenes', 'categoria'])
            ->where('estado', 'activo')
            ->where('id', '!=', $producto->id)
            ->where(function ($q) use ($producto) {
                $q->where('categoria_id', $producto->categoria_id)
                  ->orWhere('tipo', $producto->tipo);
            })
            ->latest()
            ->take(4)
            ->get();

        return view('productos.show', compact('producto', 'relacionados'));
    }

    // ── Helpers privados ───────────────────────────────────────────────────────

    private function autorizarPropietario(Producto $producto): void
    {
        if ($producto->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para gestionar esta publicación.');
        }
    }

    private function mensajeLimite(\App\Models\User $user, array $config): string
    {
        $actuales = $user->productos()->where('estado', 'activo')->count();
        return "Has alcanzado el límite de {$config['publicaciones']} publicaciones activas "
             . "de tu plan {$config['label']} ({$actuales}/{$config['publicaciones']}). "
             . "Pausa o elimina una publicación, o mejora tu plan para continuar publicando.";
    }

    private function guardarImagenes(Request $request, Producto $producto, int $ordenBase = 0): void
    {
        $destDir = public_path("uploads/productos/{$producto->id}");
        if (!is_dir($destDir)) mkdir($destDir, 0775, true);

        foreach ($request->file('imagenes', []) as $i => $archivo) {
            if (!($archivo instanceof \Illuminate\Http\UploadedFile) || !$archivo->isValid()) continue;

            $extension = strtolower($archivo->getClientOriginalExtension());
            if (!$extension) {
                $extension = match ($archivo->getMimeType()) {
                    'image/jpeg', 'image/jpg' => 'jpg',
                    'image/png'               => 'png',
                    'image/webp'              => 'webp',
                    default                   => null,
                };
            }
            if (!$extension) continue;

            $nombreArchivo = Str::uuid() . '.' . $extension;

            try {
                $archivo->move($destDir, $nombreArchivo);
            } catch (\Throwable $e) {
                continue;
            }

            ProductoImagen::create([
                'producto_id'    => $producto->id,
                'ruta'           => "uploads/productos/{$producto->id}/{$nombreArchivo}",
                'nombre_archivo' => $nombreArchivo,
                'orden'          => $ordenBase + $i,
                'estado'         => 'activo',
            ]);
        }
    }

    private function guardarDetalles(array $data, Producto $producto): void
    {
        if (empty($data['detalles'])) return;

        foreach ($data['detalles'] as $orden => $detalle) {
            if (!empty($detalle['clave']) && !empty($detalle['valor'])) {
                ProductoDetalle::create([
                    'producto_id' => $producto->id,
                    'clave'       => $detalle['clave'],
                    'valor'       => $detalle['valor'],
                    'orden'       => $orden,
                ]);
            }
        }
    }

    private function mensajesValidacion(int $maxFotos = 10): array
    {
        return [
            'titulo.required'           => 'El título del anuncio es obligatorio.',
            'descripcion.required'      => 'La descripción es obligatoria.',
            'descripcion.min'           => 'La descripción debe tener al menos 20 caracteres.',
            'precio.required'           => 'El precio es obligatorio.',
            'unidad.required'           => 'Selecciona una unidad o periodo.',
            'ubicacion.required'        => 'La ubicación es obligatoria.',
            'tipo.required'             => 'Selecciona el tipo de publicación.',
            'categoria_id.required'     => 'Selecciona una categoría.',
            'imagenes.required'         => 'Debes subir al menos 1 foto del producto.',
            'imagenes.max'              => "Tu plan permite máximo {$maxFotos} fotos por publicación.",
            'detalles.required'         => 'Debes agregar al menos 2 especificaciones técnicas.',
            'detalles.min'              => 'Debes agregar al menos 2 especificaciones técnicas.',
            'detalles.*.clave.required' => 'Completa el nombre de todas las especificaciones.',
            'detalles.*.valor.required' => 'Completa el valor de todas las especificaciones.',
        ];
    }
}