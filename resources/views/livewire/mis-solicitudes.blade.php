<div>
    <h1 class="text-xl font-semibold text-gray-900 mb-6">Mis solicitudes</h1>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 font-medium text-gray-500">Fecha</th>
                    <th class="px-4 py-3 font-medium text-gray-500">Horario</th>
                    <th class="px-4 py-3 font-medium text-gray-500">Sede</th>
                    <th class="px-4 py-3 font-medium text-gray-500">Equipo</th>
                    <th class="px-4 py-3 font-medium text-gray-500">Asignatura</th>
                    <th class="px-4 py-3 font-medium text-gray-500">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($solicitudes as $solicitud)
                    <tr wire:key="solicitud-{{ $solicitud->id }}" class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-gray-900">{{ $solicitud->fecha->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $solicitud->hora_inicio }} - {{ $solicitud->hora_fin }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $solicitud->sede->nombre }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">
                                {{ $solicitud->equipo->codigo }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $solicitud->asignatura->nombre }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                                'bg-amber-50 text-amber-700' => $solicitud->estado === 'pendiente',
                                'bg-emerald-50 text-emerald-700' => $solicitud->estado === 'confirmada',
                                'bg-gray-100 text-gray-500' => $solicitud->estado === 'cancelada',
                                'bg-red-50 text-red-700' => $solicitud->estado === 'rechazada',
                            ])>
                                {{ ucfirst($solicitud->estado) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            @if ($solicitud->estado === 'confirmada' && ! $solicitud->fecha->isPast())
                                <button type="button" wire:click="$dispatch('editar-solicitud', { solicitudId: {{ $solicitud->id }} })"
                                        class="text-sm font-medium text-brand-600 hover:text-brand-700 active:scale-[0.97] transition-transform duration-150">
                                    Editar
                                </button>
                            @endif
                            @if ($solicitud->estado === 'confirmada' && ! $solicitud->fecha->isPast())
                                <button type="button"
                                        wire:click="$dispatch('swal:confirm', {
                                            titulo: '¿Cancelar esta solicitud?',
                                            texto: 'Esta acción liberará el horario para que otro docente pueda reservarlo.',
                                            confirmarTexto: 'Sí, cancelar',
                                            cancelarTexto: 'Volver',
                                            evento: 'cancelar-solicitud',
                                            params: { solicitudId: {{ $solicitud->id }} },
                                        })"
                                        class="text-sm font-medium text-red-600 hover:text-red-700 active:scale-[0.97] transition-transform duration-150">
                                    Cancelar
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <p class="text-sm text-gray-500">Aún no tienes solicitudes.</p>
                            <a href="{{ route('disponibilidad') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors duration-150">Crear tu primera solicitud</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $solicitudes->links() }}
    </div>

    <livewire:solicitud-form />
</div>
