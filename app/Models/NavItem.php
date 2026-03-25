<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NavItem extends Model
{
    protected $fillable = [
        'label',
        'url',
        'icono',
        'orden',
        'es_acento',
        'target',
        'estado',
    ];

    protected $casts = [
        'es_acento' => 'boolean',
        'orden'     => 'integer',
    ];

    // Devuelve solo los items activos, ordenados de menor a mayor por `orden`
    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('estado', 'activo')->orderBy('orden');
    }
}