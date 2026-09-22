"use strict";

const buscador = document.getElementById("buscador");
const contenedorInformacion = document.getElementById("informacion-filtrada");

async function buscadorGeneral(){
    const response = await fetch(`/api/usuarios-admin?texto=${buscador.value}`);
    const data = await response.json();
    
    generarRegistros(data);
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

function generarRegistros(informacion) {
    contenedorInformacion.innerHTML = "";

    let registros = "";

    informacion.forEach(i => {
        registros += `
            <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-black truncate">${i.nombre_usuario}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-black font-medium">${i.nombre_institucion}</td>
                <td class="px-6 py-4 text-sm text-black font-medium">
                    ${i.email}
                </td>
                <td class="px-6 py-4 text-sm text-black font-medium">
                    ${i.nombre}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <div class="seleccionar-registro hidden">
                            <inputtype="checkbox"value="${i.id}" class="check-borrar">
                        </div>
                        <button type="button"title="Cambiar Contraseña" class="btn-cambiar-contrasena p-2 text-gray-400 hover:text-amber-500 transition-colors"data-id="${i.id}">
                            <svg class="w-5 h-5"fill="none"stroke="currentColor"viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </button>

                        <!-- Editar -->
                        <div class="acciones flex items-center justify-center gap-2">
                            <button type="button" title="Editar" class="abrir-modal-edit p-2 text-gray-400 hover:text-blue-500 transition-colors" data-id="${i.id}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Eliminar -->
                        <form action="/labores/usuarios/${i.id}/admin" method="POST" class="inline" id="form-delete-${i.id}">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">

                            <input type="hidden" name="_method" value="DELETE">

                            <input type="hidden" name="contrasena_seguridad" id="contrasena-seguridad-${i.id}">

                            <button type="button" title="Eliminar" class="p-2 text-gray-400 hover:text-red-500 transition-colors" onclick="validarYEliminar(${i.id})">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
        `;
    });

    contenedorInformacion.innerHTML = registros;
}