<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'titulo', 'descripcion', 'precio', 'unidad',
        'ubicacion', 'imagen', 'tipo', 'categoria',
        'estado', 'timer_fin',
    ];

    protected $casts = [
        'timer_fin' => 'datetime',
        'precio'    => 'decimal:2',
    ];

    // Scope para buscar
    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('titulo',     'like', "%{$termino}%")
              ->orWhere('descripcion', 'like', "%{$termino}%")
              ->orWhere('categoria', 'like', "%{$termino}%")
              ->orWhere('ubicacion', 'like', "%{$termino}%");
        });
    }
}