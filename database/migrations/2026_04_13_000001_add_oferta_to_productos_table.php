<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega soporte de ofertas/descuentos a la tabla productos.
     *
     * Lógica de precios:
     *   - precio_original: precio antes del descuento (nullable; si es null, no hay oferta)
     *   - descuento_porcentaje: calculado al guardar para evitar inconsistencias
     *   - precio (campo existente): precio actual / precio con descuento
     *   - oferta_activa: flag booleano para activar/desactivar sin borrar datos
     *   - oferta_fin: opcional, la oferta expira en esta fecha/hora
     */
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Precio antes del descuento. Si es null → producto sin oferta.
            $table->decimal('precio_original', 10, 2)->nullable()->after('precio');

            // Porcentaje calculado: ((precio_original - precio) / precio_original) * 100
            // Se guarda para evitar recalcularlo en cada consulta y facilitar filtros.
            $table->unsignedTinyInteger('descuento_porcentaje')->nullable()->after('precio_original');

            // Flag de control: permite desactivar una oferta sin perder precio_original.
            $table->boolean('oferta_activa')->default(false)->after('descuento_porcentaje');

            // Fecha de expiración opcional. NULL = sin límite de tiempo.
            $table->timestamp('oferta_fin')->nullable()->after('oferta_activa');

            // Etiqueta libre: "Liquidación", "Precio especial", "2x1", etc.
            $table->string('oferta_etiqueta')->nullable()->after('oferta_fin');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn([
                'precio_original',
                'descuento_porcentaje',
                'oferta_activa',
                'oferta_fin',
                'oferta_etiqueta',
            ]);
        });
    }
};