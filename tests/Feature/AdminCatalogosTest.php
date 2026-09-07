<?php

use App\Livewire\Admin\AsignaturasIndex;
use App\Livewire\Admin\EquiposIndex;
use App\Livewire\Admin\ProgramasIndex;
use App\Models\Asignatura;
use App\Models\Equipo;
use App\Models\Programa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('un admin puede crear un programa', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    Livewire::actingAs($admin)
        ->test(ProgramasIndex::class)
        ->set('nombre', 'Ingeniería de Producción')
        ->call('guardar')
        ->assertHasNoErrors();

    expect(Programa::where('nombre', 'Ingeniería de Producción')->exists())->toBeTrue();
});

test('un admin puede crear una asignatura', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    Livewire::actingAs($admin)
        ->test(AsignaturasIndex::class)
        ->set('nombre', 'Metodología de la Investigación')
        ->call('guardar')
        ->assertHasNoErrors();

    expect(Asignatura::where('nombre', 'Metodología de la Investigación')->exists())->toBeTrue();
});

test('un admin puede crear un videobeam', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    Livewire::actingAs($admin)
        ->test(EquiposIndex::class)
        ->set('codigo', 'VB-99')
        ->call('guardar')
        ->assertHasNoErrors();

    expect(Equipo::where('codigo', 'VB-99')->exists())->toBeTrue();
});
