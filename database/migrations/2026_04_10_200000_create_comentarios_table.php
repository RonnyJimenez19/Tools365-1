<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id();

            // Autor: puede ser usuario registrado o invitado
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->string('autor_nombre', 100);          // nombre visible
            $table->string('autor_email', 180)->nullable(); // solo para invitados

            // Contenido
            $table->text('cuerpo');
            $table->unsignedTinyInteger('calificacion')   // 1–5 estrellas
                  ->default(5);

            // Moderación
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])
                  ->default('pendiente');

            // Aparece en inicio (featured)
            $table->boolean('en_inicio')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};