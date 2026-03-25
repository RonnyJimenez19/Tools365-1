<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductoDetalle;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $fillable = [
        'titulo', 'descripcion', 'precio', 'unidad',
        'ubicacion', 'tipo', 'categoria_id',   // <-- categoria_id en lugar de categoria + imagen
        'estado', 'timer_fin',
    ];

    protected $casts = [
        'timer_fin' => 'datetime',
        'precio'    => 'decimal:2',
    ];

    // ── Relaciones ─────────────────────────────────────────────

    // Pertenece a una categoría (FK)
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Tiene muchas imágenes (carrusel de ángulos)
    public function imagenes()
    {
        return $this->hasMany(ProductoImagen::class)->orderBy('orden');
    }

    // Imagen principal (orden = 0, la primera disponible)
    public function imagenPrincipal()
    {
        return $this->hasOne(ProductoImagen::class)->orderBy('orden');
    }

    // ── Scope búsqueda ─────────────────────────────────────────

    public function scopeBuscar($query, $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('titulo',      'like', "%{$termino}%")
              ->orWhere('descripcion', 'like', "%{$termino}%")
              ->orWhere('ubicacion',   'like', "%{$termino}%")
              ->orWhereHas('categoria', fn($c) =>
                  $c->where('nombre', 'like', "%{$termino}%")
              );
        });
    }

    public function detalles(): HasMany
{
    return $this->hasMany(ProductoDetalle::class)->orderBy('orden');
}
 
}