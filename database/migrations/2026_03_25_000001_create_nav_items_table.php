<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla nav_items: opciones del navbar principal, ordenadas por el campo `orden`.
     * Se consulta vía NavComposer y se inyecta en todas las vistas que usen app.blade.php.
     */
    public function up(): void
    {
        Schema::create('nav_items', function (Blueprint $table) {
            $table->id();

            // Texto visible en el navbar
            $table->string('label');

            // URL destino — puede ser ruta relativa (/planes) o absoluta (https://...)
            $table->string('url')->default('#');

            // Clase Bootstrap Icon, ej: "bi-house-fill", "bi-tags-fill"
            $table->string('icono')->default('bi-circle');

            // Orden de aparición (menor = primero a la izquierda)
            $table->unsignedSmallInteger('orden')->default(0);

            // Si es true, se muestra con color acento y separado al extremo derecho (ms-auto)
            $table->boolean('es_acento')->default(false);

            // Abrir en nueva pestaña (_blank) u otra directiva
            $table->string('target')->nullable();

            // Solo se muestran items activos
            $table->string('estado')->default('activo'); // activo | inactivo

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nav_items');
    }
};