<?php

namespace App\Livewire;

use App\Models\Asignatura;
use App\Models\Equipo;
use App\Models\Programa;
use App\Models\Sede;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class SolicitudForm extends Component
{
    use WithFileUploads;

    public bool $mostrarModal = false;

    public ?int $solicitudId = null;

    public ?int $sede_id = null;

    public string $aula = '';

    public ?int $programa_id = null;

    public ?int $asignatura_id = null;

    public ?string $actividad = null;

    public string $fecha = '';

    public string $hora_inicio = '';

    public string $hora_fin = '';

    public ?int $equipo_id = null;

    public $evidencia = null;

    public ?string $observaciones = null;

    public function mount(): void
    {
        $this->fecha = now()->addDays(config('prestamos.dias_anticipacion'))->toDateString();
    }

    #[On('abrir-modal-solicitud')]
    public function abrirModal(?string $fecha = null, ?string $horaInicio = null, ?string $horaFin = null): void
    {
        $this->resetValidation();
        $this->reset([
            'solicitudId', 'sede_id', 'aula', 'programa_id', 'asignatura_id', 'actividad',
            'equipo_id', 'evidencia', 'observaciones',
        ]);

        $this->fecha = $fecha ?? now()->addDays(config('prestamos.dias_anticipacion'))->toDateString();
        $this->hora_inicio = $horaInicio ?? '';
        $this->hora_fin = $horaFin ?? '';

        $this->mostrarModal = true;
    }

    #[On('editar-solicitud')]
    public function abrirModalEdicion(int $solicitudId): void
    {
        $solicitud = Solicitud::findOrFail($solicitudId);

        abort_unless(
            Auth::user()->is_admin || ($solicitud->user_id === Auth::id() && $solicitud->estado === Solicitud::ESTADO_CONFIRMADA && ! $solicitud->fecha->isPast()),
            403,
        );

        $this->resetValidation();

        $this->solicitudId = $solicitud->id;
        $this->sede_id = $solicitud->sede_id;
        $this->aula = $solicitud->aula;
        $this->programa_id = $solicitud->programa_id;
        $this->asignatura_id = $solicitud->asignatura_id;
        $this->actividad = $solicitud->actividad;
        $this->fecha = $solicitud->fecha->toDateString();
        $this->hora_inicio = substr($solicitud->hora_inicio, 0, 5);
        $this->hora_fin = substr($solicitud->hora_fin, 0, 5);
        $this->equipo_id = $solicitud->equipo_id;
        $this->observaciones = $solicitud->observaciones;

        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
    }

    protected function rules(): array
    {
        return [
            'sede_id' => 'required|exists:sedes,id',
            'aula' => 'required|string|max:255',
            'programa_id' => 'required|exists:programas,id',
            'asignatura_id' => 'required|exists:asignaturas,id',
            'actividad' => 'nullable|string|max:255',
            'fecha' => 'required|date|after_or_equal:'.now()->addDays(config('prestamos.dias_anticipacion'))->toDateString(),
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'equipo_id' => 'nullable|exists:equipos,id',
            'evidencia' => 'nullable|image|max:4096',
            'observaciones' => 'nullable|string|max:1000',
        ];
    }

    public function guardar(): void
    {
        $this->validate();

        $solicitudExistente = $this->solicitudId ? Solicitud::findOrFail($this->solicitudId) : null;

        if ($solicitudExistente) {
            abort_unless(
                Auth::user()->is_admin || ($solicitudExistente->user_id === Auth::id() && $solicitudExistente->estado === Solicitud::ESTADO_CONFIRMADA && ! $solicitudExistente->fecha->isPast()),
                403,
            );
        }

        $equipoId = DB::transaction(function () use ($solicitudExistente) {
            if ($this->equipo_id) {
                if (Solicitud::seSolapa($this->equipo_id, $this->fecha, $this->hora_inicio, $this->hora_fin, $solicitudExistente?->id)) {
                    $this->addError('equipo_id', 'Ese equipo ya está reservado en el horario seleccionado.');

                    return null;
                }

                return $this->equipo_id;
            }

            $equipoDisponible = Equipo::where('activo', true)
                ->get()
                ->first(fn (Equipo $equipo) => ! Solicitud::seSolapa($equipo->id, $this->fecha, $this->hora_inicio, $this->hora_fin, $solicitudExistente?->id));

            if (! $equipoDisponible) {
                $this->addError('equipo_id', 'No hay videobeams disponibles en el horario seleccionado.');

                return null;
            }

            return $equipoDisponible->id;
        });

        if (! $equipoId) {
            return;
        }

        $evidenciaPath = $this->evidencia?->store('evidencias', 'public') ?? $solicitudExistente?->evidencia_path;

        $datos = [
            'sede_id' => $this->sede_id,
            'aula' => $this->aula,
            'programa_id' => $this->programa_id,
            'asignatura_id' => $this->asignatura_id,
            'actividad' => $this->actividad,
            'fecha' => $this->fecha,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
            'equipo_id' => $equipoId,
            'evidencia_path' => $evidenciaPath,
            'observaciones' => $this->observaciones,
        ];

        if ($solicitudExistente) {
            $solicitudExistente->update($datos);
            $mensaje = 'La solicitud fue actualizada correctamente.';
        } else {
            Solicitud::create([
                ...$datos,
                'user_id' => Auth::id(),
                'estado' => Solicitud::ESTADO_CONFIRMADA,
            ]);
            $mensaje = 'Tu solicitud fue registrada y confirmada correctamente.';
        }

        $this->reset([
            'solicitudId', 'sede_id', 'aula', 'programa_id', 'asignatura_id', 'actividad',
            'hora_inicio', 'hora_fin', 'equipo_id', 'evidencia', 'observaciones',
        ]);

        $this->mostrarModal = false;

        $this->dispatch('swal:exito', mensaje: $mensaje);
        $this->dispatch('solicitud-creada');
    }

    public function render()
    {
        return view('livewire.solicitud-form', [
            'sedes' => Sede::where('activo', true)->orderBy('nombre')->get(),
            'programas' => Programa::where('activo', true)->orderBy('nombre')->get(),
            'asignaturas' => Asignatura::where('activo', true)->orderBy('nombre')->get(),
            'equipos' => Equipo::where('activo', true)->orderBy('codigo')->get(),
        ]);
    }
}
