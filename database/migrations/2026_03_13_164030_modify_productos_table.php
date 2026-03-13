<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Quitar columna imagen (ahora vive en producto_imagenes)
            $table->dropColumn('imagen');

            // Cambiar categoria (texto) por categoria_id (FK)
            $table->dropColumn('categoria');
            $table->unsignedBigInteger('categoria_id')->nullable()->after('tipo');
            $table->foreign('categoria_id')->references('id')->on('categorias')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');
            $table->string('categoria')->nullable();
            $table->string('imagen')->nullable();
        });
    }
};