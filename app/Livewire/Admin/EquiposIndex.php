<?php

namespace App\Livewire\Admin;

use App\Models\Equipo;
use App\Models\Sede;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class EquiposIndex extends Component
{
    #[Validate('required|string|max:50')]
    public string $codigo = '';

    #[Validate('nullable|string|max:255')]
    public ?string $nombre = null;

    #[Validate('nullable|exists:sedes,id')]
    public ?int $sede_id = null;

    public ?Equipo $editing = null;

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'sede_id' => $this->sede_id,
        ];

        if ($this->editing) {
            $this->editing->update($data);
        } else {
            Equipo::create($data);
        }

        $this->reset('codigo', 'nombre', 'sede_id', 'editing');
    }

    public function editar(Equipo $equipo): void
    {
        $this->editing = $equipo;
        $this->codigo = $equipo->codigo;
        $this->nombre = $equipo->nombre;
        $this->sede_id = $equipo->sede_id;
    }

    public function cancelar(): void
    {
        $this->reset('codigo', 'nombre', 'sede_id', 'editing');
    }

    public function alternarActivo(Equipo $equipo): void
    {
        $equipo->update(['activo' => ! $equipo->activo]);
    }

    public function render()
    {
        return view('livewire.admin.equipos-index', [
            'equipos' => Equipo::with('sede')->orderBy('codigo')->get(),
            'sedes' => Sede::orderBy('nombre')->get(),
        ]);
    }
}
