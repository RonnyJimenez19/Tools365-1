<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductoDetalle;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Producto extends Model
{
    protected $fillable = [
        'user_id',
        'titulo', 'descripcion', 'precio', 'unidad',
        'ubicacion', 'tipo', 'categoria_id',
        'estado', 'timer_fin',
        // ── Ofertas ──────────────────────────────────────
        'precio_original', 'descuento_porcentaje',
        'oferta_activa', 'oferta_fin', 'oferta_etiqueta',
    ];

    protected $casts = [
        'timer_fin'             => 'datetime',
        'precio'                => 'decimal:2',
        'precio_original'       => 'decimal:2',
        'oferta_activa'         => 'boolean',
        'oferta_fin'            => 'datetime',
        'descuento_porcentaje'  => 'integer',
    ];

    // ── Relaciones ──────────────────────────────────────────────────────────────

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function imagenes()
    {
        return $this->hasMany(ProductoImagen::class)->orderBy('orden');
    }

    public function imagenPrincipal()
    {
        return $this->hasOne(ProductoImagen::class)->orderBy('orden');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(ProductoDetalle::class)->orderBy('orden');
    }

    // ── Scopes ──────────────────────────────────────────────────────────────────

    public function scopeBuscar($query, $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('titulo',       'like', "%{$termino}%")
              ->orWhere('descripcion', 'like', "%{$termino}%")
              ->orWhere('ubicacion',   'like', "%{$termino}%")
              ->orWhereHas('categoria', fn($c) =>
                  $c->where('nombre', 'like', "%{$termino}%")
              );
        });
    }

    /**
     * Devuelve productos con oferta activa.
     * Si tiene oferta_fin, solo los que no han expirado.
     */
    public function scopeConOferta($query)
    {
        return $query->where('oferta_activa', true)
                     ->where(function ($q) {
                         $q->whereNull('oferta_fin')
                           ->orWhere('oferta_fin', '>', now());
                     });
    }

    // ── Helpers ─────────────────────────────────────────────────────────────────

    /**
     * Calcula y guarda el porcentaje de descuento a partir de precio_original y precio.
     * Llamar antes de save() cuando se actualicen los precios.
     */
    public function calcularDescuento(): void
    {
        if ($this->precio_original && $this->precio_original > 0) {
            $this->descuento_porcentaje = (int) round(
                (($this->precio_original - $this->precio) / $this->precio_original) * 100
            );
        } else {
            $this->descuento_porcentaje = null;
        }
    }

    /** ¿La oferta sigue vigente en este momento? */
    public function ofertaVigente(): bool
    {
        if (! $this->oferta_activa) {
            return false;
        }
        return is_null($this->oferta_fin) || $this->oferta_fin->isFuture();
    }

    /** Ahorro absoluto (precio_original - precio). */
    public function ahorroAbsoluto(): float
    {
        if ($this->precio_original && $this->ofertaVigente()) {
            return (float) ($this->precio_original - $this->precio);
        }
        return 0.0;
    }
    public function user()
{
    return $this->belongsTo(User::class);
}

public function pedidoItems() {
    return $this->hasMany(PedidoItem::class);
}
}