<?php

use App\Livewire\SolicitudForm;
use App\Models\Asignatura;
use App\Models\Equipo;
use App\Models\Programa;
use App\Models\Sede;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('un docente puede crear una solicitud y queda confirmada automáticamente', function () {
    $user = User::factory()->create();
    $sede = Sede::factory()->create();
    $programa = Programa::factory()->create();
    $asignatura = Asignatura::factory()->create();
    $equipo = Equipo::factory()->create();

    Livewire::actingAs($user)
        ->test(SolicitudForm::class)
        ->set('sede_id', $sede->id)
        ->set('aula', 'Aula 101')
        ->set('programa_id', $programa->id)
        ->set('asignatura_id', $asignatura->id)
        ->set('fecha', now()->addDay()->toDateString())
        ->set('hora_inicio', '08:00')
        ->set('hora_fin', '10:00')
        ->set('equipo_id', $equipo->id)
        ->call('guardar')
        ->assertHasNoErrors();

    expect(Solicitud::where('user_id', $user->id)->where('estado', 'confirmada')->count())->toBe(1);
});

test('no permite reservar el mismo equipo en un horario solapado', function () {
    $user = User::factory()->create();
    $sede = Sede::factory()->create();
    $programa = Programa::factory()->create();
    $asignatura = Asignatura::factory()->create();
    $equipo = Equipo::factory()->create();

    Solicitud::factory()->create([
        'equipo_id' => $equipo->id,
        'fecha' => now()->addDay()->toDateString(),
        'hora_inicio' => '08:00',
        'hora_fin' => '10:00',
        'estado' => 'confirmada',
    ]);

    Livewire::actingAs($user)
        ->test(SolicitudForm::class)
        ->set('sede_id', $sede->id)
        ->set('aula', 'Aula 101')
        ->set('programa_id', $programa->id)
        ->set('asignatura_id', $asignatura->id)
        ->set('fecha', now()->addDay()->toDateString())
        ->set('hora_inicio', '09:00')
        ->set('hora_fin', '11:00')
        ->set('equipo_id', $equipo->id)
        ->call('guardar')
        ->assertHasErrors('equipo_id');

    expect(Solicitud::where('estado', 'confirmada')->count())->toBe(1);
});

test('rechaza solicitudes sin la anticipación mínima configurada', function () {
    $user = User::factory()->create();
    $sede = Sede::factory()->create();
    $programa = Programa::factory()->create();
    $asignatura = Asignatura::factory()->create();
    $equipo = Equipo::factory()->create();

    Livewire::actingAs($user)
        ->test(SolicitudForm::class)
        ->set('sede_id', $sede->id)
        ->set('aula', 'Aula 101')
        ->set('programa_id', $programa->id)
        ->set('asignatura_id', $asignatura->id)
        ->set('fecha', now()->toDateString())
        ->set('hora_inicio', '08:00')
        ->set('hora_fin', '10:00')
        ->set('equipo_id', $equipo->id)
        ->call('guardar')
        ->assertHasErrors('fecha');
});

test('asigna automáticamente un equipo disponible cuando no se especifica uno', function () {
    $user = User::factory()->create();
    $sede = Sede::factory()->create();
    $programa = Programa::factory()->create();
    $asignatura = Asignatura::factory()->create();
    $ocupado = Equipo::factory()->create();
    $libre = Equipo::factory()->create();

    Solicitud::factory()->create([
        'equipo_id' => $ocupado->id,
        'fecha' => now()->addDay()->toDateString(),
        'hora_inicio' => '08:00',
        'hora_fin' => '10:00',
        'estado' => 'confirmada',
    ]);

    Livewire::actingAs($user)
        ->test(SolicitudForm::class)
        ->set('sede_id', $sede->id)
        ->set('aula', 'Aula 101')
        ->set('programa_id', $programa->id)
        ->set('asignatura_id', $asignatura->id)
        ->set('fecha', now()->addDay()->toDateString())
        ->set('hora_inicio', '08:00')
        ->set('hora_fin', '10:00')
        ->call('guardar')
        ->assertHasNoErrors();

    $creada = Solicitud::where('user_id', $user->id)->first();
    expect($creada->equipo_id)->toBe($libre->id);
});
