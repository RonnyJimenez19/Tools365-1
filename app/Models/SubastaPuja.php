<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubastaPuja extends Model
{
    protected $table = 'subastas_pujas';

    protected $fillable = [
        'producto_id',
        'user_id',
        'monto',
        'ganadora',
    ];

    protected $casts = [
        'monto'    => 'decimal:2',
        'ganadora' => 'boolean',
    ];

    // ── Relaciones ─────────────────────────────────────────
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}