<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── PEDIDOS ──────────────────────────────────────────────────────────
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();

            // UUID legible para el comprador (ej. T365-00042)
            $table->string('folio', 20)->unique();

            // Comprador
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Tarjeta usada (puede ser null si no se guardó)
            $table->foreignId('tarjeta_id')->nullable()->constrained('tarjetas_guardadas')->nullOnDelete();

            // Últimos 4 dígitos al momento del pago (por si borra la tarjeta después)
            $table->string('tarjeta_ultimos_cuatro', 4)->nullable();
            $table->enum('tarjeta_tipo', ['visa', 'mastercard', 'amex', 'otro'])->nullable();

            // Totales
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            // Estado del pedido
            $table->enum('estado', ['pendiente', 'pagado', 'cancelado', 'reembolsado'])
                  ->default('pendiente');

            // Tiempo límite para completar el pago (timer 3 minutos)
            $table->timestamp('pago_limite')->nullable();

            // Fecha en que se completó el pago
            $table->timestamp('pagado_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'estado']);
        });

        // ── PEDIDO ITEMS ─────────────────────────────────────────────────────
        Schema::create('pedido_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->onDelete('cascade');
            $table->foreignId('producto_id')->constrained()->onDelete('cascade');

            // Snapshot del producto al momento del pago
            $table->string('titulo', 200);
            $table->enum('tipo_accion', ['comprar', 'rentar']);
            $table->decimal('precio_unitario', 12, 2);
            $table->unsignedSmallInteger('cantidad')->default(1);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->decimal('total_item', 12, 2);

            // Quién es el vendedor (para notificaciones)
            $table->foreignId('vendedor_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();

            $table->index('pedido_id');
            $table->index('vendedor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_items');
        Schema::dropIfExists('pedidos');
    }
};