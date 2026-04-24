<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoImagen extends Model
{
    protected $table = 'producto_imagenes';

    protected $fillable = [
        'producto_id', 'categoria_id',
        'ruta', 'nombre_archivo',
        'orden', 'descripcion', 'estado',
    ];

    // Pertenece a un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Pertenece a una categoría (clasificación de la imagen)
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * URL pública de la imagen, compatible con:
     *   - Imágenes del seeder: "Imagenes/excavadora.jpg"
     *   - Imágenes subidas:    "uploads/productos/25/uuid.jpg"
     *   - Rutas legacy con "storage/": se normalizan automáticamente.
     *
     * Uso en Blade: {{ $imagen->url }}  o  {{ $imagen->url() }}
     */
    public function getUrlAttribute(): string
    {
        $ruta = ltrim($this->ruta, '/');

        // Normalizar rutas antiguas que venían con "storage/" al inicio
        if (str_starts_with($ruta, 'storage/')) {
            $ruta = substr($ruta, strlen('storage/'));
            return asset('storage/' . $ruta);
        }

        return asset($ruta);
    }
}