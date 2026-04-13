<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ProductoImagen;
use App\Models\ProductoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicarController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Formulario de publicación */
    public function create()
    {
        $categorias = Categoria::where('estado', 'activo')->orderBy('nombre')->get();
        return view('publicar.create', compact('categorias'));
    }

    /** Guardar nueva publicación */
    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo'       => 'required|string|max:200',
            'descripcion'  => 'nullable|string|max:2000',
            'precio'       => 'required|numeric|min:0',
            'unidad'       => 'nullable|string|max:50',
            'ubicacion'    => 'nullable|string|max:150',
            'tipo'         => 'required|in:renta,venta,subasta',
            'categoria_id' => 'required|exists:categorias,id',
            'timer_fin'    => 'nullable|date|after:now',
            // Imágenes
            'imagenes'     => 'nullable|array|max:10',
            'imagenes.*'   => 'image|mimes:jpeg,jpg,png,webp|max:4096',
            // Detalles clave-valor
            'detalles'     => 'nullable|array',
            'detalles.*.clave' => 'required_with:detalles|string|max:80',
            'detalles.*.valor' => 'required_with:detalles|string|max:150',
        ]);

        $producto = Producto::create([
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

foreach ($request->file('imagenes', []) as $i => $archivo) {

    
    if (!($archivo instanceof \Illuminate\Http\UploadedFile)) {
        continue;
    }

    if (!$archivo->isValid()) {
        continue;
    }

  
    if (empty($archivo->getPathname())) {
        continue;
    }

    $extension = $archivo->getClientOriginalExtension();

    if (!$extension) {
        $extension = match ($archivo->getMimeType()) {
            'image/jpeg', 'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => null
        };
    }

    if (!$extension) {
        continue;
    }

    $nombreArchivo = Str::uuid() . '.' . $extension;

    try {
        $ruta = $archivo->storeAs(
            'Imagenes/productos/' . $producto->id,
            $nombreArchivo,
            'public'
        );

        if (!$ruta) {
            continue;
        }

    } catch (\Throwable $e) {
        continue; 
    }

    ProductoImagen::create([
        'producto_id'    => $producto->id,
        'ruta'           => 'storage/' . $ruta,
        'nombre_archivo' => $nombreArchivo,
        'orden'          => $i,
        'estado'         => 'activo',
    ]);
}

        // ── Guardar detalles clave-valor ──────────────────────────────────
        if (! empty($data['detalles'])) {
            foreach ($data['detalles'] as $orden => $detalle) {
                if (! empty($detalle['clave']) && ! empty($detalle['valor'])) {
                    ProductoDetalle::create([
                        'producto_id' => $producto->id,
                        'clave'       => $detalle['clave'],
                        'valor'       => $detalle['valor'],
                        'orden'       => $orden,
                    ]);
                }
            }
        }

        return redirect()->route('inicio')
            ->with('success', '¡Tu herramienta fue publicada exitosamente!');
    }
}