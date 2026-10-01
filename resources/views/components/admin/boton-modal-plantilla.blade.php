@props(['modalPlantilla'])
<button wire:click="plantillas" class="bg-[#7B1FA3] hover:bg-[#6A1B8E] text-white px-4 md:px-5 py-2.5 rounded-xl text-xs md:text-sm font-bold transition-all shadow-lg shadow-purple-100 flex items-center gap-2 active:scale-95">
    <p class="w-4 h-4 text-white">
        Crear
    </p>
</button>

@include('admin.modal-plantilla', ['modalPlantilla' => $modalPlantilla])