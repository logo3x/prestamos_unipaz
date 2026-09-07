<?php

namespace App\Livewire;

use App\Models\Solicitud;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class MisSolicitudes extends Component
{
    use WithPagination;

    #[On('cancelar-solicitud')]
    public function cancelar(int $solicitudId): void
    {
        $solicitud = Solicitud::findOrFail($solicitudId);

        abort_unless($solicitud->user_id === Auth::id(), 403);

        if (! in_array($solicitud->estado, Solicitud::ESTADOS_ACTIVOS) || $solicitud->fecha->isPast()) {
            return;
        }

        $solicitud->update(['estado' => Solicitud::ESTADO_CANCELADA]);

        $this->dispatch('swal:exito', mensaje: 'La solicitud fue cancelada correctamente.');
    }

    #[On('solicitud-creada')]
    public function refrescar(): void
    {
        //
    }

    public function render()
    {
        return view('livewire.mis-solicitudes', [
            'solicitudes' => Solicitud::with(['sede', 'programa', 'asignatura', 'equipo'])
                ->where('user_id', Auth::id())
                ->orderByDesc('fecha')
                ->orderByDesc('hora_inicio')
                ->paginate(10),
        ]);
    }
}
