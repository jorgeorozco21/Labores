@props(['usuarios', 'bloqueo'])

<div class="bg-white rounded-[20px] border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto no-scrollbar">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="sticky top-0 z-10 bg-gray-50">
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Usuario</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nombre Completo</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tipos de Usuario</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Grupo</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Historial</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Acciones</th>
                </tr>
            </thead>
            
            <tbody id="informacion-filtrada" class="divide-y divide-gray-50">
                @foreach ($usuarios as $usuario)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <!-- Nombre de Usuario y Correo -->
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-black truncate">{{ $usuario->nombre_usuario }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ $usuario->email }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Nombre -->
                        <td class="px-6 py-4 text-sm {{ (count(json_decode($usuario->historiales_pendientes)) >= $bloqueo->limite_bloqueo && $bloqueo->limite_bloqueo != -1) ? 'text-red-600' : 'text-black' }} font-medium">
                            {{ $usuario->nombre }}
                        </td>

                        <!-- Tipo de Usuario -->
                        <td class="px-6 py-4">
                            <div class="flex flex-col h-full justify-center gap-2">     
                                <div class="flex flex-row flex-wrap gap-2">
                                    @if ($usuario->normal == "1") 
                                        <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-blue-50 text-blue-600 border border-blue-100 w-fit">
                                            Normal
                                        </span>
                                    @endif
                                    @if ($usuario->encargado == "1") 
                                        <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-purple-50 text-[#7B1FA3] border border-purple-100 w-fit">
                                            Encargado de Area
                                        </span>
                                    @endif
                                </div>
                                @if ($usuario->mantenimiento == "1") 
                                    <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-amber-50 text-amber-600 border border-amber-100 w-fit">
                                        Encargado de Mantenimiento
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Grado / Grupo -->
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if($usuario->nombreGrupo)
                                {{ $usuario->grado }}°{{ $usuario->grupo }} - {{ $usuario->nombreGrupo }} - {{ $usuario->turno }}
                            @else
                                <span class="text-gray-300">Sin Grupo</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                <button type="button" onclick="openHistorialModal({{ json_encode($usuario->historiales_pendientes) }}, {{ json_encode($usuario->historiales_recibidos) }})" 
                                    class="flex items-center gap-2 text-[#7B1FA3] hover:text-white">
                                    <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                    </div>
                                </button>
                            </div>
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <div class="seleccionar-registro hidden">
                                    <input type="checkbox" value="{{ $usuario->id }}" class="check-borrar">
                                </div>
                                <div class="acciones flex items-center justify-center gap-2">
                                    <!-- Cambiar Contraseña -->
                                    <button title="Cambiar Contraseña" class="btn-cambiar-contrasena p-2 text-gray-400 hover:text-amber-500 transition-colors" 
                                            data-id="{{ $usuario->id }}" data-url="{{ route('admin.usuarios.cambiarContrasena', $usuario->id) }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    </button>

                                    <!-- Editar -->
                                    <button title="Editar" class="abrir-modal-edit p-2 text-gray-400 hover:text-blue-500 transition-colors" data-id="{{ $usuario->id }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>

                                    <!-- Eliminar -->
                                    <form action="{{ url('/admin/usuarios/'.$usuario->id) }}" method="post" class="inline">
                                        @csrf
                                        {{ method_field('DELETE') }}
                                        <button type="submit" title="Eliminar" class="p-2 text-gray-400 hover:text-red-500 transition-colors" onclick="return confirm('¿Deseas borrar el usuario?')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $usuarios->withQueryString()->links() }}
    </div>
</div>

<div id="historial-Modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeHistorialModal()"></div>
    
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative w-full max-w-md bg-white rounded-[20px] shadow-2xl overflow-hidden transition-all duration-300">
            <!-- Encabezado -->
            <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-black tracking-wider uppercase">Reportes</h3>
            </div>

            <!-- Lista de Materiales -->
            <div class="px-6 py-6">
                <ul id="historial-Lista" class="space-y-3"></ul>
            </div>

            <!-- Cerrar Material -->
            <div class="bg-gray-50 px-6 py-4 flex justify-center">
                <button type="button" onclick="closeHistorialModal()" 
                    class="px-10 py-2 bg-[#7B1FA3] text-white text-xs font-bold rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<div id="auditoria-Modal" class="fixed inset-0 z-[150] hidden overflow-y-auto">
    <div id="fondo-auditoria" class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
    
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-[20px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-[750px]">
            <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-black tracking-wider uppercase">Auditoría</h3>
                <p class="text-[14px] font-mono text-gray-400 bg-gray-50 px-2 py-1 rounded-md" id="id-auditoria">#</p>
            </div>

            <div class="p-6 w-full overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50">
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nombre</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Fecha</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody id="contenedor-auditoria" class="divide-y divide-gray-50">
                    </tbody>
                </table>
            </div>

            <!-- Botón de Cerrar -->
            <div class="bg-gray-50 px-6 py-4 flex justify-center border-t border-gray-50">
                <button id="cerrar-auditoria" type="button" 
                    class="px-10 py-2.5 bg-[#7B1FA3] text-white text-xs font-bold rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98] cursor-pointer">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openHistorialModal(pendientes, recibidos) {
        const modal = document.getElementById('historial-Modal');
        const lista = document.getElementById('historial-Lista');

        pendientes = JSON.parse(pendientes);
        recibidos = JSON.parse(recibidos);

        lista.innerHTML = '';

        lista.innerHTML += `
            <div class="mb-1">
                <h4 class="text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 mb-1">
                    Reportes Pendientes
                </h4>
            </div>
        `;

        if (pendientes.length === 0) {
            lista.innerHTML += `<p class="text-xs text-gray-400 italic mb-4">No hay reportes pendientes.</p>`;
        } else {
            pendientes.forEach(h => {
                lista.innerHTML += `
                    <button type="button" data-id="${h.id_solicitud}" 
                            class="historial w-full group inline-flex items-center gap-2 px-3 py-1.5 bg-purple-50 hover:bg-[#7B1FA3] text-[#7B1FA3] hover:text-white border border-purple-100 rounded-xl transition-all duration-200 cursor-pointer mb-2">
                        <span class="px-1.5 py-0.5 text-[10px] font-extrabold bg-purple-200/60 group-hover:bg-white/20 text-[#7B1FA3] group-hover:text-white rounded-md transition-colors">
                            #${h.id_solicitud}
                        </span>
                        <span class="text-xs font-bold">
                            ${h.descripcion}
                        </span>
                    </button>
                `;
            });
        }

        lista.innerHTML += `
            <hr class="border-gray-100 my-4">
            <div class="mb-1">
                <h4 class="text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 mb-1">
                    Reportes Pasados
                </h4>
            </div>
        `;

        if (recibidos.length === 0) {
            lista.innerHTML += `<p class="text-xs text-gray-400 italic">No hay reportes pasados.</p>`;
        } else {
            recibidos.forEach(h => {
                lista.innerHTML += `
                    <button type="button" data-id="${h.id_solicitud}" 
                            class="historial w-full group inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 hover:bg-green-600 text-green-600 hover:text-white border border-green-100 rounded-xl transition-all duration-200 cursor-pointer mb-2">
                        <span class="px-1.5 py-0.5 text-[10px] font-extrabold bg-green-200/60 group-hover:bg-white/20 text-green-600 group-hover:text-white rounded-md transition-colors">
                            #${h.id_solicitud}
                        </span>
                        <span class="text-xs font-bold">
                            ${h.descripcion}
                        </span>
                    </button>
                `;
            });
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeHistorialModal() {
        const modal = document.getElementById('historial-Modal');
        modal.classList.add('hidden');
        document.getElementById('historial-Lista').innerHTML = "";
        document.body.classList.remove('overflow-hidden');
    }
</script>