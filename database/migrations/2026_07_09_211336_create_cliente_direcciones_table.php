<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('cliente_direcciones')) {
            Schema::create('cliente_direcciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->string('direccion', 255);
            $table->string('referencia', 255)->default('');
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->string('etiqueta', 50)->default('Principal');
            $table->boolean('es_principal')->default(false);
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });

        DB::statement("INSERT INTO cliente_direcciones (cliente_id, direccion, referencia, es_principal, created_at, updated_at)
                        SELECT id, direccion_principal, referencia_direccion, 1, NOW(), NOW()
                        FROM clientes WHERE direccion_principal IS NOT NULL AND direccion_principal != ''");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente_direcciones');
    }
};
