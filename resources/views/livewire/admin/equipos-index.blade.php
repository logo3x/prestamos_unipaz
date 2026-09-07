<div>
    <h1 class="text-xl font-semibold text-gray-900 mb-6">Equipos (Videobeams)</h1>

    <form wire:submit="guardar" class="flex flex-wrap items-end gap-4 mb-6 bg-white border border-gray-200 rounded-2xl shadow-sm p-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Código</label>
            <input type="text" wire:model="codigo" class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            @error('codigo') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex-1 min-w-[10rem]">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre <span class="text-gray-400 font-normal">(opcional)</span></label>
            <input type="text" wire:model="nombre" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Sede</label>
            <select wire:model="sede_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                <option value="">Sin sede fija</option>
                @foreach ($sedes as $sede)
                    <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-brand-600 text-white rounded-lg px-4 py-2 text-sm font-medium hover:bg-brand-700 active:scale-[0.97] transition-[transform,background-color] duration-150 ease-out">
            {{ $editing ? 'Actualizar' : 'Crear' }}
        </button>
        @if ($editing)
            <button type="button" wire:click="cancelar" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors duration-150">Cancelar</button>
        @endif
    </form>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 font-medium text-gray-500">Código</th>
                    <th class="px-4 py-3 font-medium text-gray-500">Nombre</th>
                    <th class="px-4 py-3 font-medium text-gray-500">Sede</th>
                    <th class="px-4 py-3 font-medium text-gray-500">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($equipos as $equipo)
                    <tr wire:key="equipo-{{ $equipo->id }}" class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">
                                {{ $equipo->codigo }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $equipo->nombre ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $equipo->sede?->nombre ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                                'bg-emerald-50 text-emerald-700' => $equipo->activo,
                                'bg-gray-100 text-gray-500' => ! $equipo->activo,
                            ])>
                                {{ $equipo->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-4">
                            <button wire:click="editar({{ $equipo->id }})" class="text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors duration-150">Editar</button>
                            <button wire:click="alternarActivo({{ $equipo->id }})" class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors duration-150">
                                {{ $equipo->activo ? 'Desactivar' : 'Activar' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-sm text-gray-500">Sin equipos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
