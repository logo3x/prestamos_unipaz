<?php

use App\Livewire\Admin\SolicitudesIndex;
use App\Models\Equipo;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('un admin puede confirmar una solicitud pendiente', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $solicitud = Solicitud::factory()->create(['estado' => 'pendiente']);

    Livewire::actingAs($admin)
        ->test(SolicitudesIndex::class)
        ->call('actualizarEstado', $solicitud->id, 'confirmada');

    expect($solicitud->fresh()->estado)->toBe('confirmada');
});

test('un admin puede rechazar una solicitud pendiente', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $solicitud = Solicitud::factory()->create(['estado' => 'pendiente']);

    Livewire::actingAs($admin)
        ->test(SolicitudesIndex::class)
        ->call('actualizarEstado', $solicitud->id, 'rechazada');

    expect($solicitud->fresh()->estado)->toBe('rechazada');
});

test('no permite confirmar una solicitud si el equipo ya está reservado en ese horario', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $equipo = Equipo::factory()->create();

    Solicitud::factory()->create([
        'equipo_id' => $equipo->id,
        'fecha' => now()->addDay()->toDateString(),
        'hora_inicio' => '08:00',
        'hora_fin' => '10:00',
        'estado' => 'confirmada',
    ]);

    $pendiente = Solicitud::factory()->create([
        'equipo_id' => $equipo->id,
        'fecha' => now()->addDay()->toDateString(),
        'hora_inicio' => '09:00',
        'hora_fin' => '11:00',
        'estado' => 'pendiente',
    ]);

    Livewire::actingAs($admin)
        ->test(SolicitudesIndex::class)
        ->call('actualizarEstado', $pendiente->id, 'confirmada');

    expect($pendiente->fresh()->estado)->toBe('pendiente');
});

test('un usuario no admin no puede acceder al panel de gestión de solicitudes', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get('/admin/solicitudes')
        ->assertForbidden();
});
