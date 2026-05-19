<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subastas_pujas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('monto', 12, 2);
            $table->boolean('ganadora')->default(false);
            $table->timestamps();

            $table->index(['producto_id', 'monto']);
        });

        // Añadir precio_inicial a productos para subastas
        Schema::table('productos', function (Blueprint $table) {
            $table->decimal('precio_inicial', 12, 2)->nullable()->after('precio');
            $table->decimal('incremento_minimo', 10, 2)->nullable()->default(50)->after('precio_inicial');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subastas_pujas');
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['precio_inicial', 'incremento_minimo']);
        });
    }
};