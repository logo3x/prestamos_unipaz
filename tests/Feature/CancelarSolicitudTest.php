<?php

use App\Livewire\Admin\SolicitudesIndex;
use App\Livewire\MisSolicitudes;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('un docente puede cancelar su propia solicitud confirmada y futura', function () {
    $user = User::factory()->create();
    $solicitud = Solicitud::factory()->create([
        'user_id' => $user->id,
        'fecha' => now()->addDay()->toDateString(),
        'estado' => 'confirmada',
    ]);

    Livewire::actingAs($user)
        ->test(MisSolicitudes::class)
        ->call('cancelar', $solicitud->id);

    expect($solicitud->fresh()->estado)->toBe('cancelada');
});

test('un docente no puede cancelar la solicitud de otro usuario', function () {
    $user = User::factory()->create();
    $otro = User::factory()->create();
    $solicitud = Solicitud::factory()->create([
        'user_id' => $otro->id,
        'fecha' => now()->addDay()->toDateString(),
        'estado' => 'confirmada',
    ]);

    Livewire::actingAs($user)
        ->test(MisSolicitudes::class)
        ->call('cancelar', $solicitud->id)
        ->assertForbidden();

    expect($solicitud->fresh()->estado)->toBe('confirmada');
});

test('un admin puede cancelar cualquier solicitud confirmada', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $solicitud = Solicitud::factory()->create(['estado' => 'confirmada']);

    Livewire::actingAs($admin)
        ->test(SolicitudesIndex::class)
        ->call('actualizarEstado', $solicitud->id, 'cancelada');

    expect($solicitud->fresh()->estado)->toBe('cancelada');
});
