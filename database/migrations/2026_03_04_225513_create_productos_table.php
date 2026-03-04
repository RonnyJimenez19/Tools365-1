<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->string('unidad')->nullable();        // /día, /mes, puja actual
            $table->string('ubicacion')->nullable();
            $table->string('imagen')->nullable();        // ruta en public/Imagenes/
            $table->string('tipo');                      // renta | venta | subasta
            $table->string('categoria')->nullable();     // construccion, agricultura...
            $table->string('estado')->default('activo'); // activo | inactivo
            $table->timestamp('timer_fin')->nullable();  // para subastas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};