"use strict";

const buscador = document.getElementById("buscador");
const contenedorInformacion = document.getElementById("informacion-filtrada");

async function buscadorGeneral(){
    const response = await fetch(`/api/instituciones?texto=${buscador.value}`);
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

function generarRegistros(informacion){
    contenedorInformacion.innerHTML = "";

    let registros = "";
    informacion.forEach(i => {
        registros += `
            <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-black truncate">${i.id}</p>
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4 text-sm text-black font-medium">
                    ${i.nombre}
                </td>

                <td class="px-6 py-4 text-sm text-black font-medium">
                    ${i.tag}
                </td>

                <td class="px-6 py-4 text-sm text-black font-medium">
                    ${i.clave}
                </td>

                <td class="px-6 py-4 justify-center">
                    <div class="flex justify-center">
                        <button type="button" data-id="${i.id}"
                            class="servicios flex items-center gap-2 text-[#7B1FA3] hover:text-white transition-colors"
                            title="Ver Servicios">
                            <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </td>

                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <div class="seleccionar-registro hidden">
                            <input type="checkbox" value="${i.id}" class="check-borrar">
                        </div>
                        <div class="acciones flex items-center justify-center gap-2">
                            <!-- Editar -->
                            <button title="Editar" class="abrir-modal-edit p-2 text-gray-400 hover:text-blue-500 transition-colors" data-id="${i.id}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        `;
    });

    contenedorInformacion.innerHTML = registros;
}