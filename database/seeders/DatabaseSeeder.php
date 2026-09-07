<?php

namespace Database\Seeders;

use App\Models\Asignatura;
use App\Models\Equipo;
use App\Models\Programa;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@unipaz.edu.co'],
            ['name' => 'Administrador', 'password' => 'password', 'is_admin' => true],
        );

        User::firstOrCreate(
            ['email' => 'docente@unipaz.edu.co'],
            ['name' => 'Docente de Prueba', 'password' => 'password', 'is_admin' => false],
        );

        $sedePrincipal = Sede::firstOrCreate(['nombre' => 'Sede Principal']);
        Sede::firstOrCreate(['nombre' => 'Centro Santa Lucía']);
        Sede::firstOrCreate(['nombre' => 'Colegio de las Américas (jornada nocturna)']);

        Programa::firstOrCreate(['nombre' => 'Trabajo Social']);
        Programa::firstOrCreate(['nombre' => 'Psicología']);
        Programa::firstOrCreate(['nombre' => 'Sociología']);

        Asignatura::firstOrCreate(['nombre' => 'Investigación Social I']);
        Asignatura::firstOrCreate(['nombre' => 'Trabajo Social Comunitario']);
        Asignatura::firstOrCreate(['nombre' => 'Psicología Educativa']);

        foreach (range(1, 8) as $numero) {
            Equipo::firstOrCreate(
                ['codigo' => sprintf('VB-%02d', $numero)],
                ['sede_id' => $sedePrincipal->id],
            );
        }
    }
}
