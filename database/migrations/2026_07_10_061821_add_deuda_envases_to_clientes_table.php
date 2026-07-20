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
        if (!Schema::hasColumn('clientes', 'deuda_envases')) {
            Schema::table('clientes', function (Blueprint $table) {
                $table->integer('deuda_envases')->default(0)->after('estado')->comment('Balones vacíos no devueltos');
            });
        }
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('deuda_envases');
        });
    }
};
