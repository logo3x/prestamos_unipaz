<div>
    <h1 class="text-xl font-semibold text-gray-900 mb-6">Solicitudes</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 bg-white border border-gray-200 rounded-2xl shadow-sm p-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Sede</label>
            <select wire:model.live="sede_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                <option value="">Todas</option>
                @foreach ($sedes as $sede)
                    <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Equipo</label>
            <select wire:model.live="equipo_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                <option value="">Todos</option>
                @foreach ($equipos as $equipo)
                    <option value="{{ $equipo->id }}">{{ $equipo->codigo }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha</label>
            <input type="date" wire:model.live="fecha" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Estado</label>
            <select wire:model.live="estado" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                <option value="">Todos</option>
                @foreach ($estados as $estadoOpcion)
                    <option value="{{ $estadoOpcion }}">{{ ucfirst($estadoOpcion) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 font-medium text-gray-500">Fecha</th>
                    <th class="px-4 py-3 font-medium text-gray-500">Horario</th>
                    <th class="px-4 py-3 font-medium text-gray-500">Docente</th>
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
                        <td class="px-4 py-3 text-gray-600">{{ $solicitud->user->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $solicitud->sede->nombre }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">
                                {{ $solicitud->equipo->codigo }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $solicitud->asignatura->nombre }}</td>
                        <td class="px-4 py-3">
                            <select
                                wire:change="actualizarEstado({{ $solicitud->id }}, $event.target.value)"
                                @class([
                                    'rounded-lg border-0 px-2 py-1 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-500',
                                    'bg-amber-50 text-amber-700' => $solicitud->estado === 'pendiente',
                                    'bg-emerald-50 text-emerald-700' => $solicitud->estado === 'confirmada',
                                    'bg-gray-100 text-gray-500' => $solicitud->estado === 'cancelada',
                                    'bg-red-50 text-red-700' => $solicitud->estado === 'rechazada',
                                ])>
                                @foreach ($estados as $estadoOpcion)
                                    <option value="{{ $estadoOpcion }}" @selected($solicitud->estado === $estadoOpcion)>{{ ucfirst($estadoOpcion) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button type="button" wire:click="$dispatch('editar-solicitud', { solicitudId: {{ $solicitud->id }} })"
                                    class="text-sm font-medium text-brand-600 hover:text-brand-700 active:scale-[0.97] transition-transform duration-150">
                                Editar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-12 text-center text-sm text-gray-500">No hay solicitudes con estos filtros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $solicitudes->links() }}
    </div>

    <livewire:solicitud-form />
</div>
