<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('detalles_pedido', 'envases_devueltos')) {
            Schema::table('detalles_pedido', function (Blueprint $table) {
                $table->integer('envases_devueltos')->default(0)->after('subtotal');
            });
        }
    }

    public function down(): void
    {
        Schema::table('detalles_pedido', function (Blueprint $table) {
            $table->dropColumn('envases_devueltos');
        });
    }
};
