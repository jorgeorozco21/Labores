<!-- Nombre de Usuario -->
<div>
    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nombre de Usuario</label>
    <input type="text" id="nombre-usuario-edit" name="nombre_usuario" class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-[#7B1FA3]" autocomplete="off">
</div>
<!-- Correo Electronico -->
<div>
    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Email</label>
    <input type="text" id="email-edit" name="email" readonly class="w-full px-4 py-2 bg-gray-100 border border-gray-100 rounded-xl text-gray-400" autocomplete="off">
</div>
<!-- Nombre Completo -->
<div>
    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nombre Completo</label>
    <input type="text" id="nombre-completo-edit" name="nombre" class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-[#7B1FA3]" autocomplete="off">
</div>

<div>
    <!-- Tipos de Usuario -->
    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tipos de Usuario</label>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <!-- Normal -->
        <label class="relative flex items-center gap-3 p-3 bg-gray-50 border border-gray-100 rounded-xl cursor-pointer hover:border-purple-200 transition-all group">
            <input type="hidden" name="normal" value="0">
            <input type="checkbox" id="tipo-normal-edit" name="normal" class="peer hidden">
            <div class="w-5 h-5 border-2 border-gray-300 rounded-md flex items-center justify-center peer-checked:bg-[#7B1FA3] peer-checked:border-[#7B1FA3] transition-all">
                <svg class="w-3 h-3 text-white peer-checked:scale-100 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <span class="text-xs font-bold text-gray-500 peer-checked:text-gray-800 transition-colors">Normal</span>
        </label>
        <!-- Encargado de Area -->
        <label class="relative flex items-center gap-3 p-3 bg-gray-50 border border-gray-100 rounded-xl cursor-pointer hover:border-purple-200 transition-all group">
            <input type="hidden" name="encargado" value="0">
            <input type="checkbox" id="tipo-encargado-edit" name="encargado" class="peer hidden">
            <div class="w-5 h-5 border-2 border-gray-300 rounded-md flex items-center justify-center peer-checked:bg-[#7B1FA3] peer-checked:border-[#7B1FA3] transition-all">
                <svg class="w-3 h-3 text-white peer-checked:scale-100 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <span class="text-xs font-bold text-gray-500 peer-checked:text-gray-800 transition-colors">Encargado de Area</span>
        </label>
        <!-- Encargado de Mantenimiento -->
        <label class="relative flex items-center gap-3 p-3 bg-gray-50 border border-gray-100 rounded-xl cursor-pointer hover:border-purple-200 transition-all group">
            <input type="hidden" name="mantenimiento" value="0">
            <input type="checkbox" id="tipo-mantenimiento-edit" name="mantenimiento" class="peer hidden">
            <div class="w-5 h-5 border-2 border-gray-300 rounded-md flex items-center justify-center peer-checked:bg-[#7B1FA3] peer-checked:border-[#7B1FA3] transition-all">
                <svg class="w-3 h-3 text-white peer-checked:scale-100 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <span class="text-xs font-bold text-gray-500 peer-checked:text-gray-800 transition-colors">Encargado de Mantenimiento</span>
        </label>
    </div>
</div>
<!-- Grupo (Normal) -->
<div>
    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1" id="label-grupo-edit">Grupo</label>
    <select id="grupo-edit" name="id_grupo" class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#7B1FA3]">
        @foreach ($grupos as $grupo)
            <option value="{{ $grupo->id }}">{{ $grupo->grado }}°{{ $grupo->grupo }} - {{ $grupo->nombre }} - {{ $grupo->turno }}</option>
        @endforeach
    </select>
</div>

<input type="hidden" name="id_institucion" value="{{ session('id_institucion') }}">

<!-- Boton de Actualizar -->
<button type="submit" class="w-full bg-[#7B1FA3] text-white font-bold py-3 rounded-2xl mt-4 hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">Actualizar Usuario</button>