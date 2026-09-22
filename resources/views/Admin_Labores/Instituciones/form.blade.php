<div class="space-y-4">
    <!-- Nombre de Institucion -->
    <div>
        <label for="nombre-institucion" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nombre de Institucion</label>
        <input type="text" id="nombre-institucion" name="nombre" 
                class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-[#7B1FA3] transition-all"
                placeholder="Nombre de la institucion" autocomplete="off">
    </div>
    <!-- Clave -->
    <div>
        <label for="clave" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Clave</label>
        <input type="text" id="clave" name="clave" 
                class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-[#7B1FA3] transition-all"
                placeholder="Clave de la Institucion" autocomplete="off">
    </div>
    <!-- Tag -->
    <div>
        <label for="tag" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tag</label>
        <input type="text" id="tag" name="tag" 
                class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-[#7B1FA3] transition-all"
                placeholder="Tag de la Institucion" autocomplete="off">
    </div>
    <!-- Servicios -->
    <div>
        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Servicios disponibles</label>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <!-- Labores -->
            <label class="relative flex items-center gap-3 p-3 bg-gray-50 border border-gray-100 rounded-xl cursor-pointer hover:border-purple-200 transition-all group">
                <input type="hidden" name="gestor_laboratorio" value="0">
                <input type="checkbox" id="gestor-laboratorio" name="gestor_laboratorio" value="1" class="peer hidden"> 
                <div class="w-5 h-5 border-2 border-gray-300 rounded-md flex items-center justify-center peer-checked:bg-[#7B1FA3] peer-checked:border-[#7B1FA3] transition-all">
                    <svg class="w-3 h-3 text-white peer-checked:scale-100 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-gray-500 peer-checked:text-gray-800 transition-colors">Labores</span>
            </label>
        </div>
    </div>
    
    <input type="hidden" name="contrasena_seguridad" id="contrasena-seguridad">

    <!-- Boton de Crear Institucion -->
    <div class="pt-2">
        <button onclick="validarYEnviar()" type="submit" 
                class="w-full bg-[#7B1FA3] text-white font-bold py-3 rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">
            Crear Institución
        </button>
    </div>

</div>

<script>
function validarYEnviar() {
    let contrasena = prompt('Ingresa la contraseña para realizar esta accion:');
    
    if (contrasena !== null) {
        document.getElementById('contrasena-seguridad').value = contrasena;
        
        document.getElementById('form-institucion').submit();
    }
}
</script>