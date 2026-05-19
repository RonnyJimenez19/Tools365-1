<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido_items', function (Blueprint $table) {
            // Porcentaje aplicado al momento del pago (snapshot del plan del vendedor)
            $table->decimal('comision_pct', 5, 4)->default(0)->after('total_item');
        });

        // Las columnas comision_plataforma y neto_vendedor ya existen en tu BD,
        // solo aseguramos que tengan default 0 si no lo tienen.
    }

    public function down(): void
    {
        Schema::table('pedido_items', function (Blueprint $table) {
            $table->dropColumn('comision_pct');
        });
    }
};