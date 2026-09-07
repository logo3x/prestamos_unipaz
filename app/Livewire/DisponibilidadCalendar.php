<?php

namespace App\Livewire;

use App\Models\Equipo;
use App\Models\Solicitud;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DisponibilidadCalendar extends Component
{
    public bool $embebido = false;

    protected array $colores = [
        '#2563eb', '#dc2626', '#059669', '#d97706',
        '#7c3aed', '#db2777', '#0891b2', '#65a30d',
    ];

    public function eventos(string $inicio, string $fin): array
    {
        $equipos = Equipo::orderBy('codigo')->get()->keyBy('id');

        return Solicitud::with(['asignatura', 'user'])
            ->whereIn('estado', Solicitud::ESTADOS_ACTIVOS)
            ->whereBetween('fecha', [$inicio, $fin])
            ->get()
            ->map(function (Solicitud $solicitud) use ($equipos) {
                $equipo = $equipos->get($solicitud->equipo_id);
                $colorIndex = $equipo ? $equipo->id % count($this->colores) : 0;
                $esPendiente = $solicitud->estado === Solicitud::ESTADO_PENDIENTE;

                return [
                    'title' => ($equipo?->codigo ?? 'Equipo').' - '.$solicitud->asignatura?->nombre.($esPendiente ? ' (pendiente)' : ''),
                    'start' => $solicitud->fecha->format('Y-m-d').'T'.$solicitud->hora_inicio,
                    'end' => $solicitud->fecha->format('Y-m-d').'T'.$solicitud->hora_fin,
                    'color' => $this->colores[$colorIndex],
                    'classNames' => $esPendiente ? ['fc-evento-pendiente'] : [],
                    'extendedProps' => [
                        'docente' => $solicitud->user->name,
                        'equipo' => $equipo?->codigo ?? 'Equipo',
                        'asignatura' => $solicitud->asignatura?->nombre,
                        'estado' => $solicitud->estado,
                    ],
                ];
            })
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.disponibilidad-calendar');
    }
}
