<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarjetas_guardadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Últimos 4 dígitos visibles (nunca el número completo)
            $table->string('ultimos_cuatro', 4);

            // Tipo de tarjeta detectado por el primer dígito
            $table->enum('tipo', ['visa', 'mastercard', 'amex', 'otro'])->default('otro');

            // Titular tal como aparece en la tarjeta
            $table->string('titular', 100);

            // Mes y año de vencimiento
            $table->unsignedTinyInteger('exp_mes');   // 1–12
            $table->unsignedSmallInteger('exp_anio'); // ej. 2028

            // Token simulado que representaría el token de pasarela real
            $table->string('token_simulado', 64)->unique();

            // Tarjeta predeterminada del usuario
            $table->boolean('predeterminada')->default(false);

            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarjetas_guardadas');
    }
};