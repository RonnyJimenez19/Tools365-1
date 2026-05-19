<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * 1. Renombra los valores de plan en tabla users:
 *    basico → free  (plan gratuito sin pago)
 *    pro    → basico
 *    empresarial → profesional
 *
 * 2. Agrega columna comision_plataforma a pedido_items
 *    para registrar cuánto retiene la plataforma de cada transacción.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Migrar valores de plan en users ─────────────────────────────
        // Orden importa: primero empresarial → profesional, luego pro → basico,
        // luego basico → free para no pisar valores ya migrados.
        DB::statement("UPDATE users SET plan = 'profesional' WHERE plan = 'empresarial'");
        DB::statement("UPDATE users SET plan = 'basico'      WHERE plan = 'pro'");
        DB::statement("UPDATE users SET plan = 'free'        WHERE plan = 'basico' AND plan NOT IN ('profesional')");

        // ── 2. Columna comision_plataforma en pedido_items ──────────────────
        Schema::table('pedido_items', function (Blueprint $table) {
            $table->decimal('comision_plataforma', 10, 2)->default(0)->after('total_item');
            $table->decimal('neto_vendedor',       10, 2)->default(0)->after('comision_plataforma');
        });

        // ── 3. Columna comision_plataforma en pedidos (total plataforma) ────
        Schema::table('pedidos', function (Blueprint $table) {
            $table->decimal('comision_total', 10, 2)->default(0)->after('total');
        });
    }

    public function down(): void
    {
        // Revertir renombrado de planes
        DB::statement("UPDATE users SET plan = 'basico'      WHERE plan = 'free'");
        DB::statement("UPDATE users SET plan = 'pro'         WHERE plan = 'basico'");
        DB::statement("UPDATE users SET plan = 'empresarial' WHERE plan = 'profesional'");

        Schema::table('pedido_items', function (Blueprint $table) {
            $table->dropColumn(['comision_plataforma', 'neto_vendedor']);
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('comision_total');
        });
    }
};