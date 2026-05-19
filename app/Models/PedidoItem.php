<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoItem extends Model
{
    protected $fillable = [
        'pedido_id',
        'producto_id',
        'titulo',
        'tipo_accion',
        'precio_unitario',
        'cantidad',
        'fecha_inicio',
        'fecha_fin',
        'total_item',
        'vendedor_id',
    ];

    protected $casts = [
        'fecha_inicio'    => 'date',
        'fecha_fin'       => 'date',
        'precio_unitario' => 'float',
        'total_item'      => 'float',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    /**
     * Descripción del periodo para rentas.
     */
    public function periodoRenta(): ?string
    {
        if ($this->tipo_accion !== 'rentar' || !$this->fecha_inicio) return null;
        $dias = $this->fecha_inicio->diffInDays($this->fecha_fin) + 1;
        return "{$this->fecha_inicio->format('d/m/Y')} – {$this->fecha_fin->format('d/m/Y')} ({$dias} día(s))";
    }

    
}