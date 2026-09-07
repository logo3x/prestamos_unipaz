<?php

namespace App\Livewire\Admin;

use App\Models\Equipo;
use App\Models\Sede;
use App\Models\Solicitud;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class SolicitudesIndex extends Component
{
    use WithPagination;

    #[Url]
    public ?int $sede_id = null;

    #[Url]
    public ?int $equipo_id = null;

    #[Url]
    public ?string $fecha = null;

    #[Url]
    public ?string $estado = null;

    public function actualizarEstado(int $solicitudId, string $nuevoEstado): void
    {
        abort_unless(in_array($nuevoEstado, Solicitud::estados()), 422);

        $solicitud = Solicitud::findOrFail($solicitudId);

        if ($nuevoEstado === Solicitud::ESTADO_CONFIRMADA
            && Solicitud::seSolapa($solicitud->equipo_id, $solicitud->fecha->toDateString(), $solicitud->hora_inicio, $solicitud->hora_fin, $solicitud->id)) {
            $this->dispatch('swal:error', mensaje: 'No se puede confirmar: ese equipo ya está reservado en ese horario.');

            return;
        }

        $solicitud->update(['estado' => $nuevoEstado]);

        $this->dispatch('swal:exito', mensaje: 'El estado de la solicitud fue actualizado.');
    }

    #[On('solicitud-creada')]
    public function refrescar(): void
    {
        //
    }

    public function render()
    {
        $solicitudes = Solicitud::with(['user', 'sede', 'programa', 'asignatura', 'equipo'])
            ->when($this->sede_id, fn ($query) => $query->where('sede_id', $this->sede_id))
            ->when($this->equipo_id, fn ($query) => $query->where('equipo_id', $this->equipo_id))
            ->when($this->fecha, fn ($query) => $query->where('fecha', $this->fecha))
            ->when($this->estado, fn ($query) => $query->where('estado', $this->estado))
            ->orderByDesc('fecha')
            ->orderByDesc('hora_inicio')
            ->paginate(15);

        return view('livewire.admin.solicitudes-index', [
            'solicitudes' => $solicitudes,
            'sedes' => Sede::orderBy('nombre')->get(),
            'equipos' => Equipo::orderBy('codigo')->get(),
            'estados' => Solicitud::estados(),
        ]);
    }
}
