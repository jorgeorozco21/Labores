@props(['laboratorios'])
<div class="flex flex-col md:flex-row items-end gap-4 mb-6 bg-white p-6 rounded-[20px] border border-gray-100 shadow-sm">
    <!-- Buscador -->
    <div class="flex-1 w-full">
        <label for="" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Buscar {{ (request()->is('*historial*')) ? 'Historial' : 'Solicitud' }}</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" id="buscador" placeholder="Nombre o ID {{ (request()->is('*historial*')) ? 'del Historial' : 'de Solicitud' }}..." autocomplete="off"
                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#7B1FA3]/10 focus:border-[#7B1FA3] transition-all">
        </div>
    </div>

    @if (request()->is('*computo*'))
        <div class="w-full md:w-64">
            <label for="filtro-tipo" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Tipo</label>
            <select id="filtro-tipo" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#7B1FA3] appearance-none cursor-pointer">
                <option value="Sin Filtro">Todos los Tipos</option>
                <option value="hardware">Hardware</option>
                <option value="software">Software</option>
                <option value="red">Red</option>
            </select>
        </div>
    @endif

    <!-- Laboratorios -->
    <div class="w-full md:w-64">
        <label for="filtro" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Laboratorios</label>
        <select id="filtro" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#7B1FA3] appearance-none cursor-pointer">
            <option value="Sin Filtro">Todos los laboratorios</option>
            @foreach ($laboratorios as $laboratorio)    
                <option value="{{ $laboratorio->id }}">{{ $laboratorio->nombre }}</option>
            @endforeach
        </select>
    </div>
</div>