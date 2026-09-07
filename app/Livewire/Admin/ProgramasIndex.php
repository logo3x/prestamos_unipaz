<?php

namespace App\Livewire\Admin;

use App\Models\Programa;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class ProgramasIndex extends Component
{
    #[Validate('required|string|max:255')]
    public string $nombre = '';

    public ?Programa $editing = null;

    public function guardar(): void
    {
        $this->validate();

        if ($this->editing) {
            $this->editing->update(['nombre' => $this->nombre]);
        } else {
            Programa::create(['nombre' => $this->nombre]);
        }

        $this->reset('nombre', 'editing');
    }

    public function editar(Programa $programa): void
    {
        $this->editing = $programa;
        $this->nombre = $programa->nombre;
    }

    public function cancelar(): void
    {
        $this->reset('nombre', 'editing');
    }

    public function alternarActivo(Programa $programa): void
    {
        $programa->update(['activo' => ! $programa->activo]);
    }

    public function render()
    {
        return view('livewire.admin.programas-index', [
            'programas' => Programa::orderBy('nombre')->get(),
        ]);
    }
}
