<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoDetalle extends Model
{
    protected $table = 'producto_detalles';

    protected $fillable = [
        'producto_id',
        'clave',
        'valor',
        'orden',
        'icono',
    ];

    protected $casts = [
        'orden' => 'integer',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}