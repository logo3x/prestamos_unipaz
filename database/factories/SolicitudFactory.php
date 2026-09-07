<?php

namespace Database\Factories;

use App\Models\Asignatura;
use App\Models\Equipo;
use App\Models\Programa;
use App\Models\Sede;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Solicitud>
 */
class SolicitudFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'sede_id' => Sede::factory(),
            'aula' => fake()->bothify('Aula ###'),
            'programa_id' => Programa::factory(),
            'asignatura_id' => Asignatura::factory(),
            'actividad' => fake()->sentence(),
            'fecha' => fake()->dateTimeBetween('+1 day', '+2 weeks')->format('Y-m-d'),
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
            'equipo_id' => Equipo::factory(),
            'observaciones' => null,
            'estado' => 'confirmada',
        ];
    }
}
