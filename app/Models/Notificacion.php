<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id',
        'tipo',
        'titulo',
        'cuerpo',
        'url',
        'ref_tipo',
        'ref_id',
        'leida',
        'leida_at',
    ];

    protected $casts = [
        'leida'    => 'boolean',
        'leida_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers de UI ────────────────────────────────────────────────────────

    public function icono(): string
    {
        return match($this->tipo) {
            'venta_realizada'    => 'bi-bag-check-fill',
            'renta_realizada'    => 'bi-clock-history',
            'compra_confirmada'  => 'bi-check-circle-fill',
            'pedido_cancelado'   => 'bi-x-circle-fill',
            default              => 'bi-bell-fill',
        };
    }

    public function colorClase(): string
    {
        return match($this->tipo) {
            'venta_realizada', 'renta_realizada', 'compra_confirmada' => 'notif-green',
            'pedido_cancelado'                                         => 'notif-red',
            default                                                    => 'notif-blue',
        };
    }

    /**
     * Marcar como leída.
     */
    public function marcarLeida(): void
    {
        if (!$this->leida) {
            $this->update(['leida' => true, 'leida_at' => now()]);
        }
    }

    // ── Factory helpers ──────────────────────────────────────────────────────

    public static function crearParaVendedor(User $vendedor, PedidoItem $item, Pedido $pedido): self
    {
        $accion = $item->tipo_accion === 'rentar' ? 'rentó' : 'compró';
        $tipo   = $item->tipo_accion === 'rentar' ? 'renta_realizada' : 'venta_realizada';

        return static::create([
            'user_id'  => $vendedor->id,
            'tipo'     => $tipo,
            'titulo'   => "¡Alguien {$accion} tu herramienta!",
            'cuerpo'   => "El pedido #{$pedido->folio} incluye \"{$item->titulo}\" por $" . number_format($item->total_item, 2) . " MXN.",
            'url'      => "/dashboard/ventas",
            'ref_tipo' => 'pedido',
            'ref_id'   => $pedido->id,
        ]);
    }

    public static function crearParaComprador(User $comprador, Pedido $pedido): self
    {
        return static::create([
            'user_id'  => $comprador->id,
            'tipo'     => 'compra_confirmada',
            'titulo'   => "¡Pago confirmado! Pedido #{$pedido->folio}",
            'cuerpo'   => "Tu pago de $" . number_format($pedido->total, 2) . " MXN fue procesado exitosamente.",
            'url'      => "/dashboard/compras",
            'ref_tipo' => 'pedido',
            'ref_id'   => $pedido->id,
        ]);
    }

    public static function crearCancelacion(User $user, Pedido $pedido): self
    {
        return static::create([
            'user_id'  => $user->id,
            'tipo'     => 'pedido_cancelado',
            'titulo'   => "Pedido #{$pedido->folio} cancelado",
            'cuerpo'   => "El tiempo para completar tu pago expiró. Tu carrito ha sido restaurado.",
            'url'      => "/carrito",
            'ref_tipo' => 'pedido',
            'ref_id'   => $pedido->id,
        ]);
    }
}