@if ($modalEspecificaciones)
    <div class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="cerrarModalEspecificaciones"></div>
        
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative w-full max-w-md bg-white rounded-[20px] shadow-2xl overflow-hidden transition-all duration-300">
                <!-- Encabezado -->
                <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-black tracking-wider uppercase">Especificaciones Computadora</h3>
                    <p class="text-[14px] font-mono text-gray-400 bg-gray-50 px-2 py-1 rounded-md">#{{ $computadoraSeleccionada }}</p>
                </div>

                <div class="p-4">
                    @forelse ($especificacionesComputadora as $especificacion)
                        <div>
                            <label>
                                {{ $especificacion->nombre }}
                            </label>

                            <input type="text" wire:model="especificacionesEditadas.{{ $especificacion->id }}" placeholder="Especificacion">
                            
                            <button wire:click="asignarEspecificacion({{ $especificacion->id }})">Guardar</button>
                        </div>
                    @empty
                        <p>No hay especificaciones creadas para este laboratorio.</p>
                    @endforelse
                </div>

                <!-- Boton de Cerrar -->
                <div class="bg-gray-50 px-6 py-5 flex justify-center">
                    <button type="button" wire:click="cerrarModalEspecificaciones" 
                        class="px-10 py-2 bg-[#7B1FA3] text-white text-xs font-bold rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif