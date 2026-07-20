<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('comprobantes', 'monto_total')) {
            Schema::table('comprobantes', function (Blueprint $table) {
                $table->decimal('monto_total', 8, 2)->default(0)->after('fecha_emision');
            });

            DB::statement("UPDATE comprobantes c JOIN pedidos p ON c.pedido_id = p.id SET c.monto_total = p.monto_total");
        }
    }

    public function down(): void
    {
        Schema::table('comprobantes', function (Blueprint $table) {
            $table->dropColumn('monto_total');
        });
    }
};
