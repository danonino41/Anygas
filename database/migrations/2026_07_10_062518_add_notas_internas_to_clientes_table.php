<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('clientes', 'notas_internas')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->text('notas_internas')->nullable()->after('deuda_envases');
            });
        }
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('notas_internas');
        });
    }
};
