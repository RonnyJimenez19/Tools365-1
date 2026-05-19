<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE notificaciones
            MODIFY tipo ENUM(
                'venta_realizada',
                'renta_realizada',
                'compra_confirmada',
                'pedido_cancelado',
                'sistema',
                'nueva_puja'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE notificaciones
            MODIFY tipo ENUM(
                'venta_realizada',
                'renta_realizada',
                'compra_confirmada',
                'pedido_cancelado',
                'sistema'
            ) NOT NULL
        ");
    }
};