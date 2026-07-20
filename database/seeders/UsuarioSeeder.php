<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::firstOrCreate(
            ['correo' => 'admin@anygas.com'],
            [
                'documento_identidad' => '12345678',
                'nombre_completo' => 'Admin AnyGas',
                'contrasena' => Hash::make('admin123'),
                'telefono' => '999000000',
                'rol' => 'administrador',
                'estado' => 'activo',
            ]
        );

        Usuario::firstOrCreate(
            ['correo' => 'recepcion@anygas.com'],
            [
                'documento_identidad' => '23456789',
                'nombre_completo' => 'Recepcionista AnyGas',
                'contrasena' => Hash::make('recepcion123'),
                'telefono' => '999000001',
                'rol' => 'recepcionista',
                'estado' => 'activo',
            ]
        );

        Usuario::firstOrCreate(
            ['correo' => 'motorizado@anygas.com'],
            [
                'documento_identidad' => '34567890',
                'nombre_completo' => 'Motorizado AnyGas',
                'contrasena' => Hash::make('motorizado123'),
                'telefono' => '999000002',
                'rol' => 'motorizado',
                'estado' => 'activo',
            ]
        );
    }
}
