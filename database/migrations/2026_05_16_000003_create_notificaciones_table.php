<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();

            // Destinatario
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Tipo de notificación para ícono/color
            $table->enum('tipo', [
                'venta_realizada',   // vendedor: alguien compró tu producto
                'renta_realizada',   // vendedor: alguien rentó tu producto
                'compra_confirmada', // comprador: tu compra fue confirmada
                'pedido_cancelado',  // cualquiera: pedido cancelado por timeout
                'sistema',           // genérico
            ])->default('sistema');

            $table->string('titulo', 150);
            $table->text('cuerpo');

            // Enlace opcional (ej. al pedido o al producto)
            $table->string('url', 300)->nullable();

            // Referencia polimórfica ligera (opcional, para navegar directo)
            $table->string('ref_tipo', 50)->nullable();   // 'pedido', 'producto'
            $table->unsignedBigInteger('ref_id')->nullable();

            $table->boolean('leida')->default(false);
            $table->timestamp('leida_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'leida']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};