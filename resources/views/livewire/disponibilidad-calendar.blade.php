<div>
    @unless ($embebido)
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Disponibilidad de videobeams</h1>
                <p class="text-sm text-gray-500 mt-1">Selecciona un horario disponible en el calendario o crea una solicitud directamente.</p>
            </div>
            <button type="button" wire:click="$dispatch('abrir-modal-solicitud')"
                    class="shrink-0 bg-brand-600 text-white rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-brand-700 active:scale-[0.97] transition-[transform,background-color] duration-150 ease-out">
                + Crear solicitud
            </button>
        </div>
    @endunless

    <div wire:ignore x-data="disponibilidadCalendar(false)" x-init="init()">
        <div x-ref="calendarEl" class="bg-white border border-gray-200 rounded-2xl shadow-sm p-4"></div>
    </div>

    @unless ($embebido)
        <livewire:solicitud-form />
    @endunless
</div>
