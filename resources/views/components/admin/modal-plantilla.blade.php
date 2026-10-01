@props(['modalPlantilla'])
@if ($modalPlantilla)
    <div class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="cerrarModalPlantilla"></div>
        
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative w-full max-w-md bg-white rounded-[20px] shadow-2xl overflow-hidden transition-all duration-300">
                <!-- Encabezado -->
                <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-black tracking-wider uppercase">Especificaciones Computadoras</h3>
                </div>

                <div class="p-6 space-y-4" x-data="{ mostrarInput: false }">
    
                    <button @click="mostrarInput = !mostrarInput; if(!mostrarInput) { $wire.set('nuevaEspecificacion', ''); }" type="button" 
                        class="px-4 py-2 bg-purple-100 text-[#7B1FA3] text-xs font-bold rounded-xl hover:bg-[#7B1FA3] hover:text-white transition-all">
                        <span x-text="mostrarInput ? 'Cancelar' : 'Agregar especificación'"></span>
                    </button>

                    <div x-show="mostrarInput" x-cloak class="flex space-y-3 pt-2">
                        
                        <input type="text" wire:model="nuevaEspecificacion" placeholder="Escribe la especificación..."
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#7B1FA3] transition-all">
                        
                        <button wire:confirm="¿Deseas agregar esta especificacion a todos las computadoras?" type="button" wire:click="crearPlantilla
                        " @click="mostrarInput = false"
                            class="px-5 py-2 bg-[#7B1FA3] text-white text-xs font-bold rounded-xl hover:bg-[#6A1B8E] transition-all shadow-md">
                            Guardar
                        </button>
                    </div>

                </div>

                <div class="p-4">
                    @forelse ($plantillasCreadas as $plantilla)
                        <div>
                            <input type="text" wire:model="plantillasEditadas.{{ $plantilla->id }}">
                            <button wire:click="editarPlantilla({{ $plantilla->id }})">Guardar</button>
                            <button wire:confirm="¿Deseas borrar esta especificacion en todas las computadoras?" wire:click="borrarPlantilla({{ $plantilla->id }})">Borrar</button>
                        </div>
                    @empty
                        <div>
                            <p>No hay especificaciones</p>
                        </div>
                    @endforelse
                </div>

                <!-- Boton de Cerrar -->
                <div class="bg-gray-50 px-6 py-5 flex justify-center">
                    <button type="button" wire:click="cerrarModalPlantilla" 
                        class="px-10 py-2 bg-[#7B1FA3] text-white text-xs font-bold rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif