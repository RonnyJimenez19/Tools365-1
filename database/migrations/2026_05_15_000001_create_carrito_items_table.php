<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carrito_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->onDelete('cascade');

            // 'comprar' | 'rentar'
            // Las subastas entran como 'comprar' al precio actual
            $table->enum('tipo_accion', ['comprar', 'rentar'])->default('comprar');

            // Solo para rentas
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();

            // Para ventas puede ser > 1; para renta y subasta siempre 1
            $table->unsignedSmallInteger('cantidad')->default(1);

            // Precio unitario snapshot al momento de agregar
            $table->decimal('precio_unitario', 12, 2);

            // Para rentas: días/semanas calculados; para ventas: cantidad * precio_unitario
            $table->decimal('total_calculado', 12, 2);

            // Evitar duplicados: mismo usuario + producto + tipo_accion
            $table->unique(['user_id', 'producto_id', 'tipo_accion'], 'carrito_unique');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carrito_items');
    }
};