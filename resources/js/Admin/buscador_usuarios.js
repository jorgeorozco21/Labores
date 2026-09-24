"use strict";

const contenedorInformacion = document.getElementById("informacion-filtrada");
const buscador = document.getElementById("buscador");
const filtroTipo = document.getElementById("filtrar-tipo");
const filtroGrupo = document.getElementById("filtrar-grupo");
const limite = document.getElementById("limite-bloqueo").value;

import { funcionActiva } from "./borrado_masivo";
import { ids } from "./borrado_masivo";

async function buscadorGeneral(){
    //console.log(buscador.value, filtroTipo.value, filtroGrupo.value);
    const response = await fetch(`/api/usuarios?texto=${buscador.value}&tipoUsuario=${filtroTipo.value}&grupo=${filtroGrupo.value}`);
    const data = await response.json();
    
    contenedorInformacion.innerHTML = generarRegistro(data.data);
}

// donde se almacena el temporizador
let typingTimer;
// este delay es para que despues de 300 milisegundos detecte si el usuario sigue escribiendo
const delay = 300;

// funcion para dectectar si el usuario sigue escribiendo
buscador.addEventListener("input", ()=>{
    clearTimeout(typingTimer);
    typingTimer = setTimeout(()=>{  
        buscadorGeneral();
    }, delay);
});

function generarRegistro(data){
    // obtener token
    const meta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = meta ? meta.content : '';

    let fila = '';

    //console.log(data);

    data.forEach(usuario =>{
        fila += `
            <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div>
                            <p class="text-sm font-bold text-gray-800">${usuario.nombre_usuario}</p>
                            <p class="text-xs text-gray-400">${usuario.email}</p>
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4 text-sm ${(usuario.historiales_pendientes.length >= limite && limite != -1) ? 'text-red-600' : 'text-black'} font-medium">
                    ${usuario.nombre}
                </td>

                <td class="px-6 py-4">
                    <div class="flex flex-col h-full justify-center gap-2"> 
                        <div class="flex flex-row flex-wrap gap-2">
                            ${ (usuario.normal == "1")?'<span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-blue-50 text-blue-600 border border-blue-100 w-fit">Normal</span>':'' }
                            ${ (usuario.encargado == "1")?'<span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-purple-50 text-[#7B1FA3] border border-purple-100 w-fit">Encargado de Area</span>':'' }
                        </div>
                            ${ (usuario.mantenimiento == "1")?'<span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-amber-50 text-amber-600 border border-amber-100 w-fit">Encargado de Mantenimiento</span>':'' }
                    </div>
                </td>

                <td class="px-6 py-4 text-sm text-gray-500">
                    ${(usuario.nombreGrupo) ? `${usuario.grado}°${usuario.grupo} - ${usuario.nombreGrupo} - ${usuario.turno}` : 'Sin Grupo'}
                </td>

                <td class="px-6 py-4">
                    <div class="flex justify-center">
                        <button type="button" onclick='openHistorialModal(${JSON.stringify(usuario.historiales_pendientes || [])}, ${JSON.stringify(usuario.historiales_recibidos || [])})' 
                            class="flex items-center gap-2 text-[#7B1FA3] hover:text-white">
                            <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </td>

                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <div class="seleccionar-registro ${(funcionActiva) ? "" : "hidden" }">
                            <input type="checkbox" value="${usuario.id}" class="check-borrar" ${ (ids.includes(String(usuario.id))) ? "checked" : "" }>
                        </div>
                        <div class="acciones ${ (funcionActiva) ? "hidden" : "" } flex items-center justify-center gap-2">
                            <button title="Cambiar Contraseña" class="btn-cambiar-contrasena p-2 text-gray-400 hover:text-amber-500 transition-colors" 
                                    data-id="${usuario.id}" data-url="/admin/usuarios/${usuario.id}/cambiar-contrasena">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            </button>

                            <button title="Editar" class="abrir-modal-edit p-2 text-gray-400 hover:text-blue-500 transition-colors" data-id="${usuario.id}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>

                            <form action="/admin/usuarios/${usuario.id}" method="post" class="inline">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" title="Eliminar" class="p-2 text-gray-400 hover:text-red-500 transition-colors" onclick="return confirm('¿Deseas borrar el usuario?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        `;
    });

    return fila;
}

filtroTipo.addEventListener("change", ()=>{
    if (filtroTipo.value == 'encargado' || filtroTipo.value == 'mantenimiento'){
        filtroGrupo.selectedIndex = 0;
        filtroGrupo.style.display = 'none';
        document.getElementById('filtrar-grupo-label').style.display = 'none';
    }else{
        filtroGrupo.style.display = 'flex';
        document.getElementById('filtrar-grupo-label').style.display = 'flex';
    }

    buscadorGeneral();
});

filtroGrupo.addEventListener("change", ()=>{
    buscadorGeneral();
});