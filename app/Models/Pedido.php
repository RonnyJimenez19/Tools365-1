<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
class Pedido extends Model
{
    protected $fillable = [
        'folio',
        'user_id',
        'tarjeta_id',
        'tarjeta_ultimos_cuatro',
        'tarjeta_tipo',
        'subtotal',
        'total',
        'estado',
        'pago_limite',
        'pagado_at',
    ];

    protected $casts = [
        'pago_limite' => 'datetime',
        'pagado_at'   => 'datetime',
        'subtotal'    => 'float',
        'total'       => 'float',
    ];

    // ── Relaciones ───────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tarjeta(): BelongsTo
    {
        return $this->belongsTo(Tarjeta::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Genera un folio legible: T365-00042
     */
    public static function generarFolio(): string
    {
        $ultimo = static::max('id') ?? 0;
        return 'T365-' . str_pad($ultimo + 1, 5, '0', STR_PAD_LEFT);
    }

    /**
     * ¿El timer de 3 minutos ya expiró?
     */
    public function timerExpirado(): bool
    {
        return $this->pago_limite && now()->gt($this->pago_limite);
    }

    /**
     * Segundos restantes para el pago (0 si expiró).
     */
    public function segundosRestantes(): int
    {
        if (!$this->pago_limite || $this->timerExpirado()) return 0;
        return (int) now()->diffInSeconds($this->pago_limite);
    }

    /**
     * Color/badge del estado para la UI.
     */
    public function badgeEstado(): array
    {
        return match($this->estado) {
            'pagado'      => ['class' => 'badge-pagado',    'label' => 'Pagado'],
            'cancelado'   => ['class' => 'badge-cancelado', 'label' => 'Cancelado'],
            'reembolsado' => ['class' => 'badge-reembolsado','label' => 'Reembolsado'],
            default        => ['class' => 'badge-pendiente', 'label' => 'Pendiente'],
        };
    }
}