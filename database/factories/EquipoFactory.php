<?php

namespace Database\Factories;

use App\Models\Equipo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Equipo>
 */
class EquipoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigo' => 'VB-'.fake()->unique()->numberBetween(1, 999),
            'nombre' => null,
            'sede_id' => null,
            'activo' => true,
        ];
    }
}
