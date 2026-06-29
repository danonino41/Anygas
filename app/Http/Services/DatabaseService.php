<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Exception;

class DatabaseService
{
    public static function CheckDatabaseConnection(): array
    {
        try {
            DB::connection()->getPdo();

            if (!Schema::hasTable('pedidos') || !Schema::hasTable('usuarios')) {
                return ['status' => 'error', 'message' => 'Base de datos incompleta (tablas faltantes).'];
            }

            return ['status' => 'success', 'message' => 'Conexión y estructura OK.'];

        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Error de conexión: ' . $e->getMessage()];
        }
    }

    public static function limpiarLogsAntiguos()
    {
        DB::table('logs_sistema')->where('created_at', '<', now()->subDays(30))->delete();
    }
}