<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pedidos') && Schema::hasColumn('pedidos', 'estado')) {
            DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM('pendiente','asignado','en_camino','en_ruta','entregado','cancelado') DEFAULT 'pendiente'");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pedidos') && Schema::hasColumn('pedidos', 'estado')) {
            DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM('pendiente','asignado','en_camino','entregado','cancelado') DEFAULT 'pendiente'");
        }
    }
};
