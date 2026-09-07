<?php

namespace App\Models;

use Database\Factories\SolicitudFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'sede_id', 'aula', 'programa_id', 'asignatura_id', 'actividad',
    'fecha', 'hora_inicio', 'hora_fin', 'equipo_id', 'evidencia_path',
    'observaciones', 'estado',
])]
class Solicitud extends Model
{
    /** @use HasFactory<SolicitudFactory> */
    use HasFactory;

    protected $table = 'solicitudes';

    public const ESTADO_PENDIENTE = 'pendiente';

    public const ESTADO_CONFIRMADA = 'confirmada';

    public const ESTADO_CANCELADA = 'cancelada';

    public const ESTADO_RECHAZADA = 'rechazada';

    /**
     * Estados que ocupan un horario (bloquean disponibilidad del equipo).
     *
     * @var array<int, string>
     */
    public const ESTADOS_ACTIVOS = [self::ESTADO_PENDIENTE, self::ESTADO_CONFIRMADA];

    /**
     * @return array<int, string>
     */
    public static function estados(): array
    {
        return [
            self::ESTADO_PENDIENTE,
            self::ESTADO_CONFIRMADA,
            self::ESTADO_CANCELADA,
            self::ESTADO_RECHAZADA,
        ];
    }

    protected function casts(): array
    {
        return [
            'fecha' => 'date:Y-m-d',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function programa(): BelongsTo
    {
        return $this->belongsTo(Programa::class);
    }

    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class);
    }

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }

    /**
     * Determina si el equipo indicado ya tiene una solicitud pendiente o
     * confirmada que se solapa con el rango de fecha/hora dado.
     */
    public static function seSolapa(
        int $equipoId,
        string $fecha,
        string $horaInicio,
        string $horaFin,
        ?int $excludingId = null,
    ): bool {
        return static::where('equipo_id', $equipoId)
            ->where('fecha', $fecha)
            ->whereIn('estado', self::ESTADOS_ACTIVOS)
            ->when($excludingId, fn ($query) => $query->whereNot('id', $excludingId))
            ->where('hora_inicio', '<', $horaFin)
            ->where('hora_fin', '>', $horaInicio)
            ->exists();
    }
}
