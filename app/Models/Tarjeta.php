<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Tarjeta extends Model
{
    protected $table = 'tarjetas_guardadas';

    protected $fillable = [
        'user_id',
        'ultimos_cuatro',
        'tipo',
        'titular',
        'exp_mes',
        'exp_anio',
        'token_simulado',
        'predeterminada',
    ];

    protected $casts = [
        'predeterminada' => 'boolean',
        'exp_mes'        => 'integer',
        'exp_anio'       => 'integer',
    ];

    // ── Relaciones ───────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Detecta el tipo de tarjeta a partir del primer dígito del número completo.
     */
    public static function detectarTipo(string $numero): string
    {
        $numero = preg_replace('/\D/', '', $numero);

        if (str_starts_with($numero, '4'))                          return 'visa';
        if (preg_match('/^5[1-5]/', $numero))                      return 'mastercard';
        if (preg_match('/^3[47]/', $numero))                       return 'amex';
        return 'otro';
    }

    /**
     * Genera un token simulado único para identificar la tarjeta.
     */
    public static function generarToken(): string
    {
        return Str::random(64);
    }

    /**
     * Label legible para la UI.
     */
    public function labelCorto(): string
    {
        $marca = match($this->tipo) {
            'visa'       => 'Visa',
            'mastercard' => 'Mastercard',
            'amex'       => 'Amex',
            default      => 'Tarjeta',
        };
        return "{$marca} •••• {$this->ultimos_cuatro}";
    }

    /**
     * Ícono Bootstrap Icons según el tipo.
     */
    public function icono(): string
    {
        return match($this->tipo) {
            'visa', 'mastercard', 'amex' => 'bi-credit-card-2-front-fill',
            default                       => 'bi-credit-card',
        };
    }

    /**
     * ¿La tarjeta está vencida?
     */
    public function estaVencida(): bool
    {
        $hoy = now();
        if ($this->exp_anio < $hoy->year) return true;
        if ($this->exp_anio === $hoy->year && $this->exp_mes < $hoy->month) return true;
        return false;
    }
}