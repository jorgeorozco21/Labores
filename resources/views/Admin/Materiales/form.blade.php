<div class="space-y-4">
    <!-- Nombre del Material -->
    <div>
        <label for="nombre" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nombre del Material</label>
        <input wire:model="nombre" type="text" id="nombre" name="nombre"
                class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-[#7B1FA3] transition-all"
                placeholder="Arduino" autocomplete="off">
        @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <!-- Descripcion del Material -->
    <div>
        <label for="descripcion" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Descripcion</label>
        <textarea wire:model="descripcion" id="descripcion" name="descripcion"
                class="w-full max-h-32 px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-[#7B1FA3] transition-all"
                placeholder="Arduino" autocomplete="off"></textarea>
        @error('descripcion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <!-- Tipo de Prestamo -->
    <label for="tipo" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tipo de Prestamos</label>
    <select id="tipo" wire:model="tipoPrestamo" name="tipo" class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#7B1FA3] transition-all">
        <option value="">Seleccione un tipo de prestamo...</option>
        <option value="prestamos por unidad">Prestamos por Unidad</option>
        <option value="prestamos por cantidad">Prestamos por Cantidad</option>
    </select>
    @error('tipoPrestamo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

    <!-- Tipo de Devolucion -->
    <label for="tipo" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tipo de Devolucion</label>
    <select id="tipo" wire:model="tipoDevolucion" name="tipo" class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#7B1FA3] transition-all">
        <option value="">Seleccione un tipo de devolucion...</option>
        <option value="retornable">Retornable</option>
        <option value="consumible">Consumible</option>
    </select>
    @error('tipoDevolucion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

    <!-- Boton de Crear Material -->
    <div class="pt-2">
        <button type="submit" value="Crear Material" 
            class="w-full bg-[#7B1FA3] text-white font-bold py-3 rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">
            {{ $esEditar ? 'Editar' : 'Crear' }} Material
        </button>
    </div>
</div>