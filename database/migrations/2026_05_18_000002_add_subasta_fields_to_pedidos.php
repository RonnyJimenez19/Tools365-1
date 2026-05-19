<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            // Indica que el pedido fue generado por una subasta ganada
            $table->boolean('es_subasta')->default(false)->after('estado');
            // Referencia al producto de subasta (para mostrar info al pagar)
            $table->foreignId('subasta_producto_id')
                  ->nullable()
                  ->after('es_subasta')
                  ->constrained('productos')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['subasta_producto_id']);
            $table->dropColumn(['es_subasta', 'subasta_producto_id']);
        });
    }
};