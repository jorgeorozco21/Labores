<div class="space-y-4">
    <div>
        <label for="nombre" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nombre</label>
        <input type="text" wire:model="nombre" id="nombre" name="nombre" class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-[#7B1FA3] transition-all" autocomplete="off">
        @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="tipo_grupo" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tipo de Grupo</label>
        <select id="turno" name="tipo_grupo"  wire:model="tipoGrupo" class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#7B1FA3] transition-all">
            <option value="">Selecciona un tipo de grupo...</option>
            <option value="principal">Principal</option>
            <option value="secundario">Secundario</option>
        </select>
        @error('tipoGrupo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="laboratorios" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Laboratorios</label>
        <div class="flex gap-2">
            <select id="laboratorios"  wire:model="selectLaboratorio"
                class="flex-1 px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#7B1FA3] transition-all">
                <option value="">Selecciona un laboratorio...</option>
                @foreach ($laboratorios as $laboratorio)
                    <option value="{{ $laboratorio->id }}">{{ $laboratorio->nombre }}</option>
                @endforeach 
            </select>

            <button type="button" wire:click="agregarLaboratorio"
                class="px-4 py-2 bg-[#7B1FA3] text-white font-bold rounded-xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98] whitespace-nowrap">
                Agregar
            </button>
        </div>
        @error('laboratoriosSeleccionados') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div id="laboratorios-agregados" class="max-h-[120px] overflow-y-auto pr-1">
        @foreach ($laboratoriosSeleccionados as $laboratorio)
            <div class="group flex items-center justify-between p-1 mb-2.5 bg-white hover:bg-[#F5F3FF] border-l-4 border-[#7B1FA3] rounded-r-xl border-gray-100 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-gray-800 group-hover:text-[#7B1FA3] transition-colors truncate">
                            {{ $laboratorio['nombre'] }}
                        </p>
                    </div>
                </div>
                <button type="button" class="eliminar-laboratorio shrink-0 p-1.5 ml-2 text-gray-400 hover:text-red-600 transition-all active:scale-95" 
                        wire:click="eliminarLaboratorio({{ $laboratorio['id'] }})" title="Eliminar">
                    ✕
                </button>
            </div>
        @endforeach
    </div>
    
    <div class="pt-2">
        <button type="submit" value="Crear Grupo"
                class="w-full bg-[#7B1FA3] text-white font-bold py-3 rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">
                Agregar Grupo
        </button>
    </div>
</div>


<input type="hidden" id="inf-laboratorios" name="laboratorios">
<input type="hidden" name="id_institucion" value="{{ session("id_institucion") }}">