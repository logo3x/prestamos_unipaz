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

test('un docente puede editar su propia solicitud confirmada y futura', function () {
    $user = User::factory()->create();
    $solicitud = Solicitud::factory()->create([
        'user_id' => $user->id,
        'estado' => 'confirmada',
        'fecha' => now()->addDay()->toDateString(),
        'aula' => 'Aula 100',
    ]);

    Livewire::actingAs($user)
        ->test(SolicitudForm::class)
        ->call('abrirModalEdicion', $solicitud->id)
        ->assertSet('aula', 'Aula 100')
        ->set('aula', 'Aula 200')
        ->call('guardar')
        ->assertHasNoErrors();

    expect($solicitud->fresh()->aula)->toBe('Aula 200');
    expect($solicitud->fresh()->estado)->toBe('confirmada');
});

test('un docente no puede editar una solicitud con fecha ya pasada', function () {
    $user = User::factory()->create();
    $solicitud = Solicitud::factory()->create([
        'user_id' => $user->id,
        'estado' => 'confirmada',
        'fecha' => now()->subDay()->toDateString(),
    ]);

    Livewire::actingAs($user)
        ->test(SolicitudForm::class)
        ->call('abrirModalEdicion', $solicitud->id)
        ->assertForbidden();
});

test('un docente no puede editar una solicitud cancelada', function () {
    $user = User::factory()->create();
    $solicitud = Solicitud::factory()->create([
        'user_id' => $user->id,
        'estado' => 'cancelada',
        'fecha' => now()->addDay()->toDateString(),
    ]);

    Livewire::actingAs($user)
        ->test(SolicitudForm::class)
        ->call('abrirModalEdicion', $solicitud->id)
        ->assertForbidden();
});

test('un docente no puede editar la solicitud confirmada de otro usuario', function () {
    $user = User::factory()->create();
    $otro = User::factory()->create();
    $solicitud = Solicitud::factory()->create([
        'user_id' => $otro->id,
        'estado' => 'confirmada',
        'fecha' => now()->addDay()->toDateString(),
    ]);

    Livewire::actingAs($user)
        ->test(SolicitudForm::class)
        ->call('abrirModalEdicion', $solicitud->id)
        ->assertForbidden();
});

test('un admin puede editar cualquier solicitud sin importar el estado', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $sede = Sede::factory()->create();
    $programa = Programa::factory()->create();
    $asignatura = Asignatura::factory()->create();
    $equipo = Equipo::factory()->create();
    $solicitud = Solicitud::factory()->create(['estado' => 'cancelada']);

    Livewire::actingAs($admin)
        ->test(SolicitudForm::class)
        ->call('abrirModalEdicion', $solicitud->id)
        ->set('sede_id', $sede->id)
        ->set('programa_id', $programa->id)
        ->set('asignatura_id', $asignatura->id)
        ->set('equipo_id', $equipo->id)
        ->set('aula', 'Aula Admin')
        ->call('guardar')
        ->assertHasNoErrors();

    expect($solicitud->fresh()->aula)->toBe('Aula Admin');
});
