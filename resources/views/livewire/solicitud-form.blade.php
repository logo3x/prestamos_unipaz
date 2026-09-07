<div>
    @if ($mostrarModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center p-4 animate-fade-in-up" style="animation-duration:0.2s">
            <div wire:click="cerrarModal" class="absolute inset-0 bg-gray-900/50"></div>

            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                    <h1 class="text-lg font-semibold text-gray-900">{{ $solicitudId ? 'Editar solicitud' : 'Solicitud de préstamo de videobeam' }}</h1>
                    <button type="button" wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600 transition-colors duration-150 text-xl leading-none">&times;</button>
                </div>

                <div class="px-6 pt-4">
                    <p class="text-sm text-gray-500 mb-4">
                        @if ($solicitudId)
                            Actualiza los datos de la solicitud.
                        @else
                            Registra tu solicitud con al menos {{ config('prestamos.dias_anticipacion') }} día(s) de anticipación. Quedará confirmada de inmediato si hay disponibilidad.
                        @endif
                    </p>
                </div>

                <form wire:submit="guardar" class="space-y-5 px-6 pb-6">
                    <div>
                        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Información de la clase</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Sede</label>
                                <select wire:model="sede_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                    <option value="">Seleccione una sede</option>
                                    @foreach ($sedes as $sede)
                                        <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('sede_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Aula</label>
                                <input type="text" wire:model="aula" placeholder="Ejemplo: Aula 202" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                @error('aula') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Programa</label>
                                <select wire:model="programa_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                    <option value="">Seleccione un programa</option>
                                    @foreach ($programas as $programa)
                                        <option value="{{ $programa->id }}">{{ $programa->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('programa_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Asignatura</label>
                                <select wire:model="asignatura_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                    <option value="">Seleccione una asignatura</option>
                                    @foreach ($asignaturas as $asignatura)
                                        <option value="{{ $asignatura->id }}">{{ $asignatura->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('asignatura_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Actividad <span class="text-gray-400 font-normal">(opcional)</span></label>
                            <input type="text" wire:model="actividad" placeholder="Describa brevemente la actividad" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-5">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Fecha, horario y equipo</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha de la clase</label>
                                <input type="date" wire:model="fecha" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                @error('fecha') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Videobeam</label>
                                <select wire:model="equipo_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                    <option value="">Cualquiera disponible</option>
                                    @foreach ($equipos as $equipo)
                                        <option value="{{ $equipo->id }}">{{ $equipo->codigo }} @if($equipo->nombre) - {{ $equipo->nombre }} @endif</option>
                                    @endforeach
                                </select>
                                @error('equipo_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Hora de inicio</label>
                                <input type="time" wire:model="hora_inicio" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                @error('hora_inicio') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Hora de finalización</label>
                                <input type="time" wire:model="hora_fin" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                @error('hora_fin') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-5">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Evidencia y observaciones</h2>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Imagen de evidencia <span class="text-gray-400 font-normal">(opcional)</span></label>
                            <input type="file" wire:model="evidencia" class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-gray-100 file:text-gray-700 file:text-sm file:font-medium hover:file:bg-gray-200">
                            @error('evidencia') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Observaciones <span class="text-gray-400 font-normal">(opcional)</span></label>
                            <textarea wire:model="observaciones" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"></textarea>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" wire:click="cerrarModal" class="flex-1 bg-gray-100 text-gray-700 rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-gray-200 active:scale-[0.97] transition-[transform,background-color] duration-150 ease-out">
                            Cancelar
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="guardar"
                                class="flex-1 bg-brand-600 text-white rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-brand-700 active:scale-[0.97] disabled:opacity-60 disabled:cursor-not-allowed transition-[transform,background-color,opacity] duration-150 ease-out">
                            <span wire:loading.remove wire:target="guardar">{{ $solicitudId ? 'Guardar cambios' : 'Enviar solicitud' }}</span>
                            <span wire:loading wire:target="guardar">Guardando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
