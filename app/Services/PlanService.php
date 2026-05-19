<?php

namespace App\Services;

/**
 * PlanService — Fuente única de verdad para planes, límites, precios y comisiones.
 *
 * Planes disponibles: 'free', 'basico', 'profesional'
 */
class PlanService 
{
    // ── Configuración de planes ────────────────────────────────────────────────

    /**
     * Devuelve el catálogo completo de planes con sus metadatos.
     */
    public static function catalogo(): array
    {
        return [
            'free' => [
                'label'             => 'Free',
                'emoji'             => '🌱',
                'precio_mensual'    => 0,
                'publicaciones'     => 5,
                'fotos'             => 3,
                'comision_venta'    => 0.12,   // 12 %
                'comision_renta'    => 0.15,   // 15 %
                'destacadas'        => 0,
                'soporte'           => 'Email (48 h)',
                'videos'            => 0,
                'insignia'          => false,
                'estadisticas'      => false,
                'api'               => false,
            ],
            'basico' => [
                'label'             => 'Básico',
                'emoji'             => '🚀',
                'precio_mensual'    => 199,
                'publicaciones'     => 20,
                'fotos'             => 6,
                'comision_venta'    => 0.10,   // 10 %
                'comision_renta'    => 0.12,   // 12 %
                'destacadas'        => 2,
                'soporte'           => 'Email (24 h)',
                'videos'            => 1,
                'insignia'          => true,
                'estadisticas'      => false,
                'api'               => false,
            ],
            'profesional' => [
                'label'             => 'Profesional',
                'emoji'             => '💎',
                'precio_mensual'    => 599,
                'publicaciones'     => PHP_INT_MAX,  // ilimitadas
                'fotos'             => 10,
                'comision_venta'    => 0.08,   //  8 %
                'comision_renta'    => 0.08,   //  8 %
                'destacadas'        => 10,
                'soporte'           => 'Prioritario 24/7',
                'videos'            => 3,
                'insignia'          => true,
                'estadisticas'      => true,
                'api'               => true,
            ],
        ];
    }

    // ── Helpers de plan ────────────────────────────────────────────────────────

    /**
     * Devuelve la config del plan de un usuario (o plan 'free' si no existe).
     */
    public static function deUsuario(\App\Models\User $user): array
    {
        $plan = $user->plan ?? 'free';
        return self::catalogo()[$plan] ?? self::catalogo()['free'];
    }

    /**
     * Devuelve la config de un plan por clave.
     */
    public static function de(string $plan): array
    {
        return self::catalogo()[$plan] ?? self::catalogo()['free'];
    }

    /**
     * Lista de claves de planes válidos.
     */
    public static function claves(): array
    {
        return array_keys(self::catalogo());
    }

    // ── Precios ────────────────────────────────────────────────────────────────

    /**
     * Precio mensual de un plan.
     */
    public static function precioMensual(string $plan): float
    {
        return (float) (self::catalogo()[$plan]['precio_mensual'] ?? 0);
    }

    /**
     * Precio total pagado al contratar anual (mensual × 12 − 10 %).
     *
     * Ejemplo: Básico → 199 × 12 × 0.90 = 2,149.20
     */
    public static function precioAnualTotal(string $plan): float
    {
        return round(self::precioMensual($plan) * 12 * 0.90, 2);
    }

    /**
     * Precio mensual equivalente si se paga anual (total / 12).
     *
     * Útil para mostrar "$179/mes" en lugar del total.
     */
    public static function precioMensualEquivalenteAnual(string $plan): float
    {
        return round(self::precioAnualTotal($plan) / 12, 2);
    }

    /**
     * Ahorro total al pagar anual vs mensual (mensual × 12 × 10 %).
     */
    public static function ahorroAnual(string $plan): float
    {
        return round(self::precioMensual($plan) * 12 * 0.10, 2);
    }

    // ── Comisiones ─────────────────────────────────────────────────────────────

    /**
     * Calcula la comisión que retiene la plataforma sobre un monto.
     *
     * @param  string $plan       Plan del VENDEDOR
     * @param  string $tipoAccion 'comprar' | 'rentar'
     * @param  float  $monto      Monto bruto de la transacción
     * @return float  Comisión en pesos
     */
    public static function calcularComision(string $plan, string $tipoAccion, float $monto): float
    {
        $config = self::de($plan);
        $tasa   = $tipoAccion === 'rentar'
            ? $config['comision_renta']
            : $config['comision_venta'];

        return round($monto * $tasa, 2);
    }

    /**
     * Neto que recibe el vendedor después de comisión.
     */
    public static function netoVendedor(string $plan, string $tipoAccion, float $monto): float
    {
        return round($monto - self::calcularComision($plan, $tipoAccion, $monto), 2);
    }

    // ── Validación de límites ──────────────────────────────────────────────────

    /**
     * ¿Puede el usuario publicar un producto más?
     */
    public static function puedePublicar(\App\Models\User $user): bool
    {
        $config = self::deUsuario($user);
        $limite = $config['publicaciones'];

        if ($limite === PHP_INT_MAX) return true;

        $actuales = $user->productos()->where('estado', 'activo')->count();
        return $actuales < $limite;
    }

    /**
     * Cuántas publicaciones activas le quedan al usuario.
     * Devuelve PHP_INT_MAX si es ilimitado.
     */
    public static function publicacionesRestantes(\App\Models\User $user): int
    {
        $config = self::deUsuario($user);
        $limite = $config['publicaciones'];

        if ($limite === PHP_INT_MAX) return PHP_INT_MAX;

        $actuales = $user->productos()->where('estado', 'activo')->count();
        return max(0, $limite - $actuales);
    }

    /**
     * Porcentaje de uso de publicaciones (0-100).
     */
    public static function pctPublicaciones(\App\Models\User $user): int
    {
        $config = self::deUsuario($user);
        $limite = $config['publicaciones'];

        if ($limite === PHP_INT_MAX) return 0;

        $actuales = $user->productos()->where('estado', 'activo')->count();
        return min(100, (int) round($actuales / $limite * 100));
    }

    /**
     * Jerarquía numérica de planes para comparar upgrades.
     */
    public static function jerarquia(string $plan): int
    {
        return match($plan) {
            'free'         => 0,
            'basico'       => 1,
            'profesional'  => 2,
            default        => 0,
        };
    }

    /**
     * ¿El plan $a es superior al plan $b?
     */
    public static function esSuperior(string $a, string $b): bool
    {
        return self::jerarquia($a) > self::jerarquia($b);
    }
}