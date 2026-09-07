<?php

namespace App\Livewire\Admin;

use App\Models\Asignatura;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class AsignaturasIndex extends Component
{
    #[Validate('required|string|max:255')]
    public string $nombre = '';

    public ?Asignatura $editing = null;

    public function guardar(): void
    {
        $this->validate();

        if ($this->editing) {
            $this->editing->update(['nombre' => $this->nombre]);
        } else {
            Asignatura::create(['nombre' => $this->nombre]);
        }

        $this->reset('nombre', 'editing');
    }

    public function editar(Asignatura $asignatura): void
    {
        $this->editing = $asignatura;
        $this->nombre = $asignatura->nombre;
    }

    public function cancelar(): void
    {
        $this->reset('nombre', 'editing');
    }

    public function alternarActivo(Asignatura $asignatura): void
    {
        $asignatura->update(['activo' => ! $asignatura->activo]);
    }

    public function render()
    {
        return view('livewire.admin.asignaturas-index', [
            'asignaturas' => Asignatura::orderBy('nombre')->get(),
        ]);
    }
}
