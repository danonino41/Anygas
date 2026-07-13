<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM('pendiente','asignado','en_camino','en_ruta','entregado','cancelado') DEFAULT 'pendiente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM('pendiente','asignado','en_camino','entregado','cancelado') DEFAULT 'pendiente'");
    }
};
