<div>
    <h1 class="text-xl font-semibold text-gray-900 mb-6">Asignaturas</h1>

    <form wire:submit="guardar" class="flex items-end gap-3 mb-6 bg-white border border-gray-200 rounded-2xl shadow-sm p-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre</label>
            <input type="text" wire:model="nombre" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            @error('nombre') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
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
                    <th class="px-4 py-3 font-medium text-gray-500">Nombre</th>
                    <th class="px-4 py-3 font-medium text-gray-500">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($asignaturas as $asignatura)
                    <tr wire:key="asignatura-{{ $asignatura->id }}" class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-gray-900">{{ $asignatura->nombre }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                                'bg-emerald-50 text-emerald-700' => $asignatura->activo,
                                'bg-gray-100 text-gray-500' => ! $asignatura->activo,
                            ])>
                                {{ $asignatura->activo ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-4">
                            <button wire:click="editar({{ $asignatura->id }})" class="text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors duration-150">Editar</button>
                            <button wire:click="alternarActivo({{ $asignatura->id }})" class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors duration-150">
                                {{ $asignatura->activo ? 'Desactivar' : 'Activar' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-12 text-center text-sm text-gray-500">Sin asignaturas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
