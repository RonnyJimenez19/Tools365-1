<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class CarritoItem extends Model
{
    protected $fillable = [
        'user_id',
        'producto_id',
        'tipo_accion',
        'fecha_inicio',
        'fecha_fin',
        'cantidad',
        'precio_unitario',
        'total_calculado',
    ];

    protected $casts = [
        'fecha_inicio'    => 'date',
        'fecha_fin'       => 'date',
        'precio_unitario' => 'decimal:2',
        'total_calculado' => 'decimal:2',
        'cantidad'        => 'integer',
    ];

    // ── Relaciones ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Calcula y actualiza total_calculado según el tipo_accion.
     *
     * Renta  → precio_unitario × días entre fecha_inicio y fecha_fin
     * Compra → precio_unitario × cantidad
     */
    public function recalcularTotal(): void
    {
        if ($this->tipo_accion === 'rentar' && $this->fecha_inicio && $this->fecha_fin) {
            $dias = max(1, Carbon::parse($this->fecha_inicio)
                ->diffInDays(Carbon::parse($this->fecha_fin)));
            $this->total_calculado = round($this->precio_unitario * $dias, 2);
        } else {
            $this->total_calculado = round($this->precio_unitario * $this->cantidad, 2);
        }
    }

    /**
     * Días de renta (null si no aplica).
     */
    public function diasRenta(): ?int
    {
        if ($this->tipo_accion !== 'rentar' || !$this->fecha_inicio || !$this->fecha_fin) {
            return null;
        }
        return max(1, Carbon::parse($this->fecha_inicio)
            ->diffInDays(Carbon::parse($this->fecha_fin)));
    }
}