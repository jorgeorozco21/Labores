<div class="space-y-4">
    <div>
        <label for="material" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Material</label>
        <select wire:model="idMaterial" class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#7B1FA3] transition-all">
            <option value="">Seleccione un material...</option>
            @foreach ($materiales as $material)
                <option value="{{ $material->id }}">{{ $material->nombre }}</option>
            @endforeach
        </select>
        @error('idMaterial') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
        <label for="cantidad" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Cantidad</label>
        <input wire:model="cantidadTotal" type="number" id="cantidad" name="cantidad_total" class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-[#7B1FA3] transition-all" autocomplete="off">
        @error('cantidadTotal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div class="pt-2">
        <button type="submit" value="Agregar Inventario"
                class="w-full bg-[#7B1FA3] text-white font-bold py-3 rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">
                {{ $esEditar ? 'Editar' : 'Agregar' }} Inventario
        </button>
    </div>
</div>
