<?php

namespace App\Livewire\Admin;

use App\Models\Sede;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class SedesIndex extends Component
{
    #[Validate('required|string|max:255')]
    public string $nombre = '';

    public ?Sede $editing = null;

    public function guardar(): void
    {
        $this->validate();

        if ($this->editing) {
            $this->editing->update(['nombre' => $this->nombre]);
        } else {
            Sede::create(['nombre' => $this->nombre]);
        }

        $this->reset('nombre', 'editing');
    }

    public function editar(Sede $sede): void
    {
        $this->editing = $sede;
        $this->nombre = $sede->nombre;
    }

    public function cancelar(): void
    {
        $this->reset('nombre', 'editing');
    }

    public function alternarActivo(Sede $sede): void
    {
        $sede->update(['activo' => ! $sede->activo]);
    }

    public function render()
    {
        return view('livewire.admin.sedes-index', [
            'sedes' => Sede::orderBy('nombre')->get(),
        ]);
    }
}
