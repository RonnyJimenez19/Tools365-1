<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comentario extends Model
{
    protected $fillable = [
        'user_id',
        'autor_nombre',
        'autor_email',
        'cuerpo',
        'calificacion',
        'estado',
        'en_inicio',
    ];

    protected $casts = [
        'en_inicio'    => 'boolean',
        'calificacion' => 'integer',
    ];

    // ── Relaciones ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    /** Solo comentarios visibles al público */
    public function scopeAprobados($query)
    {
        return $query->where('estado', 'aprobado');
    }

    /** Los que aparecen en la sección de inicio */
    public function scopeEnInicio($query)
    {
        return $query->where('en_inicio', true);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /** Devuelve las estrellas como string de íconos Bootstrap */
    public function estrellasHtml(): string
    {
        $filled = str_repeat('<i class="bi bi-star-fill text-warning"></i>', $this->calificacion);
        $empty  = str_repeat('<i class="bi bi-star text-warning"></i>', 5 - $this->calificacion);
        return $filled . $empty;
    }

    /** Iniciales del autor para avatar */
    public function iniciales(): string
    {
        $partes = explode(' ', trim($this->autor_nombre));
        $ini = strtoupper(substr($partes[0], 0, 1));
        if (isset($partes[1])) {
            $ini .= strtoupper(substr($partes[1], 0, 1));
        }
        return $ini;
    }
}