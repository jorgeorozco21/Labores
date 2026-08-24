"use strict";


const contenedorAuditorias = document.getElementById('contenedor-auditorias');
let idAud;
let bandAuditoria = false;

document.addEventListener('click', (e)=>{
    const auditoria = e.target.closest('.auditoria');

    if (auditoria){
        const id = auditoria.dataset.id;

        consultarAuditoria(id);
        idAud = id;
        bandAuditoria = true;

        document.getElementById('id-auditoria').innerHTML = `#${id}`;
        const modal = document.getElementById('auditoria-modal');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    const cerrarAuditoria = e.target.closest('.cerrar-modal-auditoria');

    if (cerrarAuditoria){
        contenedorAuditorias.innerHTML = "";
        bandAuditoria = false;
        idAud = null;
    }
});

async function consultarAuditoria(id){
    const response = await fetch(`/admin/informes-reportes/laboratorios/laboratorio-normal/auditorias?id=${id}`);
    const data = await response.json();

    generarAuditorias(data);
}


function generarAuditorias(informacion){
    if (informacion.length === 0) {
        contenedorAuditorias.innerHTML = `
            <div class="mb-4 p-4 bg-[#F7F6F8] rounded-2xl relative group transition-all cursor-default">
                <p class="text-[11px] text-gray-700 font-bold leading-relaxed line-clamp-3">
                    No hay auditorias.
                </p>
            </div>
        `;
    }else{
        let reportes = `
            <table class="w-full text-left border-collapse min-w-[7 text-center00px]">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Usuario</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Estado</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
        `;
    
        informacion.forEach(a => {
            const infoUsuario = JSON.parse(a.info_usuario);

            reportes += `
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div>
                                <p class="text-sm font-bold text-gray-800">${infoUsuario.nombre}</p>
                                <p class="text-xs text-gray-400">${infoUsuario.email}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 text-[10px] text-center font-bold rounded-lg bg-green-50 text-green-600 border border-green-100 uppercase">
                            ${a.estado}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">${a.fecha}</td>
                </tr>
            `;

        });
        
        reportes += `
                </tbody>
            </table>
        `;
    
        contenedorAuditorias.innerHTML = reportes;
    }
}

setInterval(()=>{
    if (bandAuditoria) consultarAuditoria(idAud);
},5000);

const contenedorInformacion = document.getElementById("informacion-filtrada");
const buscador = document.getElementById("buscador");
const filtroTipo = document.getElementById("filtro-tipo");

async function buscadorGeneral(){
    const response = await fetch(`/api/admin/informes-reportes/laboratorios/laboratorio-normal/buscador?idLab=${document.getElementById('id-lab').value}&texto=${buscador.value}&filtro=${filtroTipo.value}`);
    const data = await response.json();
    
    generarRegistro(data);
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

filtroTipo.addEventListener("change", ()=>{
    buscadorGeneral();
});

setInterval(()=>{
    buscadorGeneral();
},5000);

function generarRegistro(informacion){
    contenedorInformacion.innerHTML = '';

    let filas = '';

    informacion.forEach(r =>{
        const infoUsuario = JSON.parse(r.info_usuario);

        filas += `
            <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-4 text-sm text-black font-medium text-center">
                    ${r.id}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-800 truncate">${infoUsuario.nombre}</p>
                            <p class="text-[10px] text-gray-400 font-medium">${infoUsuario.email}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-black font-medium text-center">
                    ${r.nombre}
                </td>
                <td class="px-6 py-4 text-sm text-black font-medium text-center">
                    ${r.cantidad}
                </td>
                <td class="px-6 py-4 justify-center">
                    <div class="flex justify-center">
                        <button type="button" 
                            onclick="openMaterialModal('${r.id}', '${r.descripcion}')" 
                            class="flex items-center gap-2 text-[#7B1FA3] hover:text-white transition-colors"
                            title="Ver Descripcion">
                            <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center">
                        <button data-id="${r.id}"
                            class="auditoria flex items-center gap-2 text-[#7B1FA3] hover:text-white transition-colors">
                            <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 text-center">
                    ${r.fecha}
                </td>
            </tr>
        `;
    });

    contenedorInformacion.innerHTML = filas;
}