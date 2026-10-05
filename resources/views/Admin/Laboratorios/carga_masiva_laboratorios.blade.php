<!-- Cargar Archivo -->
<div class="relative group">
    <input type="file" wire:model="archivo" class="w-full text-xs text-gray-400 file:mr-4 file:py-3 file:px-5 
        file:rounded-2xl file:border-0 file:text-[11px] file:font-bold file:bg-[#7B1FA3] file:text-white 
        hover:file:bg-[#6A1B8E] file:transition-all cursor-pointer bg-white border border-gray-100 rounded-2xl shadow-sm">
    @error('archivo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
</div>
<!-- Boton Subir Archivo -->
<button type="submit" class="w-full bg-[#7B1FA3] hover:bg-[#6A1B8E] text-white px-6 py-2.5 rounded-xl text-xs font-bold transition-all shadow-md shadow-purple-100 active:scale-95 text-center">
    Subir Archivo
</button>
