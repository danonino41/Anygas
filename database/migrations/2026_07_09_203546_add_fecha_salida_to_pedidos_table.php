<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pedidos', 'fecha_salida')) {
            Schema::table('pedidos', function (Blueprint $table) {
                $table->dateTime('fecha_salida')->nullable()->after('fecha_registro');
            });
        }
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('fecha_salida');
        });
    }
};
