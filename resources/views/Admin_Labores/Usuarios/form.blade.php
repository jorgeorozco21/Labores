<div class="space-y-4">
    <!-- Nombre de Usuario -->
    <div>
        <label for="nombre-usuario" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nombre de Usuario</label>
        <input type="text" id="nombre-usuario" name="nombre_usuario" 
                class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-[#7B1FA3] transition-all"
                placeholder="tag_complemento" autocomplete="off">
    </div>
    <!-- Correo Electronico -->
    <div>
        <label for="email" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Email</label>
        <input type="text" id="email" name="email" 
                class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-[#7B1FA3] transition-all"
                placeholder="correo@ejemplo.com" autocomplete="off">
    </div>
    <!-- Nombre Completo -->
    <div>
        <label for="nombre-completo" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nombre Completo</label>
        <input type="text" id="nombre-completo" name="nombre" 
                class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:border-[#7B1FA3] transition-all"
                placeholder="Nombre y Apellidos" autocomplete="off">
    </div>

    <!-- Select de Institucion -->
    <div id="contenedor-institucion">
        <label for="institucion" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Institucion</label>
        <select id="institucion" name="id_institucion" class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#7B1FA3] transition-all">
            @foreach ($instituciones as $institucion)
                <option value="{{ $institucion->id }}">{{ $institucion->nombre }}</option>
            @endforeach
        </select>
    </div>
    
    <input type="hidden" name="contrasena_seguridad" id="contrasena-seguridad">

    <!-- Boton de Crear Usuario -->
    <div class="pt-2">
        <button type="submit" onclick="validarYEnviar()"
                class="w-full bg-[#7B1FA3] text-white font-bold py-3 rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">
            Crear Usuario
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