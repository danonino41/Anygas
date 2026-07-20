<?php

namespace Database\Seeders;

use App\Models\TipoPago;
use Illuminate\Database\Seeder;

class TipoPagoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Efectivo', 'estado' => 'activo'],
            ['nombre' => 'Tarjeta Visa / Mastercard', 'estado' => 'activo'],
            ['nombre' => 'Yape', 'estado' => 'activo'],
            ['nombre' => 'Plin', 'estado' => 'activo'],
        ];

        foreach ($tipos as $tipo) {
            TipoPago::firstOrCreate(
                ['nombre' => $tipo['nombre']],
                $tipo
            );
        }
    }
}
