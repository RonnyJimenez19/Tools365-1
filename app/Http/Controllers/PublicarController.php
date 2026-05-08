<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ProductoImagen;
use App\Models\ProductoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PublicarController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── Mis publicaciones ──────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Producto::where('user_id', Auth::id())
            ->with(['categoria', 'imagenes'])
            ->latest();

        // Filtro por estado
        if ($request->filled('estado') && $request->estado !== 'todos') {
            $query->where('estado', $request->estado);
        }

        // Filtro por tipo
        if ($request->filled('tipo') && $request->tipo !== 'todos') {
            $query->where('tipo', $request->tipo);
        }

        // Búsqueda por título
        if ($request->filled('q')) {
            $query->where('titulo', 'like', '%' . $request->q . '%');
        }

        $productos = $query->paginate(10)->withQueryString();

        // Conteos por estado para las pestañas
        $conteos = [
            'todos'     => Producto::where('user_id', Auth::id())->count(),
            'activo'    => Producto::where('user_id', Auth::id())->where('estado', 'activo')->count(),
            'pausado'   => Producto::where('user_id', Auth::id())->where('estado', 'pausado')->count(),
            'vendido'   => Producto::where('user_id', Auth::id())->where('estado', 'vendido')->count(),
            'eliminado' => Producto::where('user_id', Auth::id())->where('estado', 'eliminado')->count(),
        ];

        return view('publicar.index', compact('productos', 'conteos'));
    }

    // ── Formulario de publicación ──────────────────────────────────────────────
    public function create()
    {
        $categorias = Categoria::where('estado', 'activo')->orderBy('nombre')->get();
        return view('publicar.create', compact('categorias'));
    }

    // ── Guardar nueva publicación ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo'           => 'required|string|max:200',
            'descripcion'      => 'required|string|min:20|max:2000',
            'precio'           => 'required|numeric|min:0',
            'unidad'           => 'required|string|max:50',
            'ubicacion'        => 'required|string|max:150',
            'tipo'             => 'required|in:renta,venta,subasta',
            'categoria_id'     => 'required|exists:categorias,id',
            'timer_fin'        => 'nullable|date|after:now',
            'imagenes'         => 'required|array|min:3',
            'imagenes.*'       => 'image|mimes:jpeg,jpg,png,webp|max:4096',
            'detalles'         => 'required|array|min:2',
            'detalles.*.clave' => 'required|string|max:80',
            'detalles.*.valor' => 'required|string|max:150',
        ], $this->mensajesValidacion());

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

    // ── Formulario de edición ──────────────────────────────────────────────────
    public function edit(Producto $producto)
    {
        $this->autorizarPropietario($producto);

        $categorias = Categoria::where('estado', 'activo')->orderBy('nombre')->get();
        $producto->load(['imagenes' => fn($q) => $q->orderBy('orden'), 'detalles' => fn($q) => $q->orderBy('orden')]);

        return view('publicar.edit', compact('producto', 'categorias'));
    }

    // ── Actualizar publicación ─────────────────────────────────────────────────
    public function update(Request $request, Producto $producto)
    {
        $this->autorizarPropietario($producto);

        $data = $request->validate([
            'titulo'           => 'required|string|max:200',
            'descripcion'      => 'required|string|min:20|max:2000',
            'precio'           => 'required|numeric|min:0',
            'unidad'           => 'required|string|max:50',
            'ubicacion'        => 'required|string|max:150',
            'tipo'             => 'required|in:renta,venta,subasta',
            'categoria_id'     => 'required|exists:categorias,id',
            'timer_fin'        => 'nullable|date|after:now',
            // imágenes opcionales en edición (puede no subir nuevas)
            'imagenes'         => 'nullable|array|max:10',
            'imagenes.*'       => 'image|mimes:jpeg,jpg,png,webp|max:4096',
            'detalles'         => 'nullable|array',
            'detalles.*.clave' => 'required_with:detalles|string|max:80',
            'detalles.*.valor' => 'required_with:detalles|string|max:150',
            // IDs de imágenes existentes a eliminar
            'eliminar_imagenes'   => 'nullable|array',
            'eliminar_imagenes.*' => 'integer|exists:producto_imagenes,id',
        ], $this->mensajesValidacion());

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

        // Eliminar imágenes marcadas
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

        // Subir nuevas imágenes
        if ($request->hasFile('imagenes')) {
            $ordenActual = $producto->imagenes()->max('orden') + 1;
            $this->guardarImagenes($request, $producto, $ordenActual);
        }

        // Reemplazar detalles
        if (!empty($data['detalles'])) {
            $producto->detalles()->delete();
            $this->guardarDetalles($data, $producto);
        }

        return redirect()->route('mis-publicaciones.index')
            ->with('success', 'Publicación actualizada correctamente.');
    }

    // ── Cambiar estado (pausar / reactivar / marcar vendido) ──────────────────
    public function cambiarEstado(Request $request, Producto $producto)
    {
        $this->autorizarPropietario($producto);

        $request->validate([
            'estado' => 'required|in:activo,pausado,vendido',
        ]);

        $producto->update(['estado' => $request->estado]);

        $mensajes = [
            'activo'  => 'Publicación reactivada.',
            'pausado' => 'Publicación pausada.',
            'vendido' => 'Publicación marcada como vendida.',
        ];

        return back()->with('success', $mensajes[$request->estado]);
    }

    // ── Eliminar publicación (soft: estado = eliminado) ────────────────────────
    public function destroy(Producto $producto)
    {
        $this->autorizarPropietario($producto);

        $producto->update(['estado' => 'eliminado']);

        return redirect()->route('mis-publicaciones.index')
            ->with('success', 'Publicación eliminada.');
    }

    // ── Vista de detalle de estado de UNA publicación ─────────────────────────
    public function show(Producto $producto)
    {
        $this->autorizarPropietario($producto);
        $producto->load(['imagenes' => fn($q) => $q->orderBy('orden'), 'detalles', 'categoria']);

        return view('publicar.show', compact('producto'));
    }

    // ── Helpers privados ──────────────────────────────────────────────────────

    private function autorizarPropietario(Producto $producto): void
    {
        if ($producto->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para gestionar esta publicación.');
        }
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

    private function mensajesValidacion(): array
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
            'imagenes.required'         => 'Debes subir al menos 3 fotos del producto.',
            'imagenes.min'              => 'Debes subir al menos 3 fotos del producto.',
            'detalles.required'         => 'Debes agregar al menos 2 especificaciones técnicas.',
            'detalles.min'              => 'Debes agregar al menos 2 especificaciones técnicas.',
            'detalles.*.clave.required' => 'Completa el nombre de todas las especificaciones.',
            'detalles.*.valor.required' => 'Completa el valor de todas las especificaciones.',
        ];
    }
}