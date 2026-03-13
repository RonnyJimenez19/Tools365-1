<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto_imagenes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id')->references('id')->on('productos')->cascadeOnDelete();

            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->foreign('categoria_id')->references('id')->on('categorias')->nullOnDelete();

            // Ruta relativa dentro de public/, ej: "Imagenes/construccion/andamio.jpg"
            $table->string('ruta');
            $table->string('nombre_archivo');

            // Orden para el carrusel (0 = imagen principal)
            $table->unsignedTinyInteger('orden')->default(0);

            // Descripción opcional del ángulo: 'frontal', 'lateral', 'detalle'…
            $table->string('descripcion')->nullable();

            $table->string('estado')->default('activo'); // activo | inactivo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_imagenes');
    }
};