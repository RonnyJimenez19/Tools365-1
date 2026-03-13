<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();           // 'construccion', 'agricultura'…
            $table->string('nombre');                   // 'Construcción', 'Agricultura'…
            $table->string('icono')->nullable();        // 'bi-building', 'bi-tree'…
            $table->string('color')->nullable();        // 'primary', 'success'…
            $table->string('estado')->default('activo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};