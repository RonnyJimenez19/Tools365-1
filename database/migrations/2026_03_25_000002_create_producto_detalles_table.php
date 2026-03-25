<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla producto_detalles: pares clave-valor asociados a cada producto.
     * Ejemplos de uso:
     *   - clave: "Capacidad de carga"  valor: "500 kg"
     *   - clave: "Año de fabricación"  valor: "2021"
     *   - clave: "Marca"               valor: "Caterpillar"
     *   - clave: "Combustible"         valor: "Diésel"
     *
     * Un producto puede tener muchos detalles (relación hasMany).
     */
    public function up(): void
    {
        Schema::create('producto_detalles', function (Blueprint $table) {
            $table->id();

            // FK al producto
            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id')
                  ->references('id')
                  ->on('productos')
                  ->cascadeOnDelete();

            // Nombre del atributo, ej: "Capacidad", "Marca", "Año"
            $table->string('clave');

            // Valor del atributo, ej: "500 kg", "Caterpillar", "2021"
            $table->string('valor');

            // Orden de visualización dentro de la ficha del producto
            $table->unsignedSmallInteger('orden')->default(0);

            // Icono opcional para acompañar el detalle en la UI (Bootstrap Icon)
            $table->string('icono')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_detalles');
    }
};