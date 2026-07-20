<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition(): array
    {
        return [
            'documento_identidad' => fake()->unique()->numerify('########'),
            'nombre_completo' => fake()->name(),
            'correo' => fake()->unique()->safeEmail(),
            'contrasena' => bcrypt('password'),
            'telefono' => fake()->numerify('9########'),
            'rol' => fake()->randomElement(['administrador', 'recepcionista', 'motorizado']),
            'estado' => 'activo',
        ];
    }

    public function administrador(): static
    {
        return $this->state(fn() => ['rol' => 'administrador']);
    }

    public function recepcionista(): static
    {
        return $this->state(fn() => ['rol' => 'recepcionista']);
    }

    public function motorizado(): static
    {
        return $this->state(fn() => ['rol' => 'motorizado']);
    }
}
