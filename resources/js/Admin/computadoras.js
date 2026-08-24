"use strict";

const contenedorReportes = document.getElementById('contenedor-reportes');
const contenedorAuditorias = document.getElementById('contenedor-auditorias');
let bandReportes = false;
let idRep;
let bandAuditoria = false;
let idAud;

document.addEventListener('click', function(e){
    const reportes = e.target.closest('.reportes');

    if (reportes){
        const id = reportes.dataset.id;
        bandReportes = true;
        idRep = id;
        consultarReportes(id);

        document.getElementById('numero-computadora').innerHTML = `#${reportes.dataset.computadora}`;
        const modal = document.getElementById('reportes-modal');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    const reporte = e.target.closest('.reporte');

    if (reporte){
        const id = reporte.dataset.idsolicitud;

        bandAuditoria = true;
        idAud = id;

        consultarAuditoria(id);

        document.getElementById('id-auditoria').innerHTML = `#${id}`;
        const modal = document.getElementById('auditoria-modal');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    const cambiarEstado = e.target.closest('.cambiar-estado');

    if (cambiarEstado){
        if (confirm('Deseas cambiar el estado del equipo ??')){
            const id = cambiarEstado.dataset.id;

            editarEstado(id);
        }
    }

    const reemplazar = e.target.closest('.reemplazar');

    if (reemplazar){
        if (confirm('El equipo ha sido reemplazado ??')){
            const id = reemplazar.dataset.id;

            reemplazarEquipo(id);
        }
    }

    const cerrarReportes = e.target.closest('.cerrar-modal-reportes');

    if (cerrarReportes){
        contenedorReportes.innerHTML = "";
        bandReportes = false;
        idRep = null;
    }

    const cerrarAuditoria = e.target.closest('.cerrar-modal-auditoria');

    if (cerrarAuditoria){
        contenedorAuditorias.innerHTML = "";
        bandAuditoria = false;
        idAud = null;
    }
});

async function consultarReportes(id){
    const response = await fetch(`/admin/informes/laboratorios/laboratorio-computo/reportes?id=${id}`);
    const data = await response.json();

    generarReportes(data);
}

function generarReportes(informacion){
    contenedorReportes.innerHTML = '';

    if (informacion.length === 0) {
        contenedorReportes.innerHTML = `
            <div class="mb-4 p-4 bg-[#F7F6F8] rounded-2xl relative group transition-all cursor-default">
                <p class="text-[11px] text-gray-700 font-bold leading-relaxed line-clamp-3">
                    No hay reportes.
                </p>
            </div>
        `;
    }else{
        let resportes = '';
    
        informacion.forEach(r => {
            resportes += `
                <div data-idsolicitud="${r.id}" class="reporte mb-4 p-4 bg-[#F7F6F8] rounded-2xl border-2 border-red-200 relative group hover:shadow-md hover:border-red-600 transition-all cursor-default">
                    <p class="text-[11px] text-gray-700 font-bold leading-relaxed line-clamp-3 uppercase">
                        ${r.tipo}
                    </p>
                    <p class="text-[11px] text-gray-500 font-bold leading-relaxed line-clamp-3">
                        ${r.descripcion}
                    </p>
                </div>
            `;
        });
    
        contenedorReportes.innerHTML = resportes;
    }
}

async function consultarAuditoria(id){
    const response = await fetch(`/admin/informes/laboratorios/laboratorio-computo/auditorias?id=${id}`);
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
            <table class="w-full text-left border-collapse min-w-[700px]">
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

async function editarEstado(id){
    const datos = {
        'id_computadora': id
    };

    try{
        const respuesta = await fetch(`/admin/informes/laboratorios/laboratorio-computo/editar-computadora-${id}`,{
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(datos)
        });

        const resultado = await respuesta.json();

        if (respuesta.ok){
            alert("Informacion actualizada correctamente");
        }else{
            alert("Error: " + resultado.message);
        }
    }catch (error){
        console.error("Error al editar:", error);
    }
}

async function reemplazarEquipo(id){

    try{
        const respuesta = await fetch(`/admin/informes/laboratorios/laboratorio-computo/reemplazar-computadora-${id}`,{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
        });

        const resultado = await respuesta.json();

        if (respuesta.ok){
            alert("Informacion actualizada correctamente");
        }else{
            alert("Error: " + resultado.message);
        }
    }catch (error){
        console.error("Error al editar:", error);
    }
}

document.getElementById('nueva-computadora').addEventListener('click', ()=>{{
    if (confirm('Deseas agregar una nueva computadora ??')){
        agregarEquipo();
    }
}});

async function agregarEquipo(){
    try{
        const respuesta = await fetch(`/admin/informes/laboratorios/laboratorio-computo/crear-computadora-${document.getElementById('id-lab').value}`,{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
        });

        const resultado = await respuesta.json();

        if (respuesta.ok){
            alert("Computadora creada correctamente");
        }else{
            alert("Error: " + resultado.message);
        }
    }catch (error){
        console.error("Error al editar:", error);
    }
}

const contenedorInformacion = document.getElementById("informacion-filtrada");
const buscador = document.getElementById("buscador");
const filtroTipo = document.getElementById("filtrar-tipo");

async function buscadorGeneral(){
    const response = await fetch(`/api/admin/informes/laboratorios/laboratorio-computo/buscador?idLab=${document.getElementById('id-lab').value}&texto=${buscador.value}&filtro=${filtroTipo.value}`);
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

setInterval(() =>{
    buscadorGeneral();
},5000);

filtroTipo.addEventListener("change", ()=>{
    buscadorGeneral();
});

function generarRegistro(informacion){
    contenedorInformacion.innerHTML = '';

    let filas = '';

    informacion.forEach(c =>{
        filas += `
            <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-4 text-sm text-black font-medium text-center">${c.numero_computadora }</td>
                <td class="px-6 py-4 text-center">
                <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg ${ (c.estado == 'activo')?'bg-green-50 text-green-600 border border-green-100':'bg-red-50 text-red-600 border border-red-100' } w-fit">
                        ${c.estado}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center">
                        <button data-id="${c.id}" data-computadora="${c.numero_computadora}"
                            class="reportes flex items-center gap-2 text-[#7B1FA3] hover:text-white">
                            <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center">
                        <button data-id="${c.id}" class="cambiar-estado flex items-center gap-2 p-2 rounded-lg text-sm text-black hover:text-[#7B1FA3] hover:bg-purple-50 transition-colors group">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7h-9M20 7l-3-3M20 7l-3 3M4 17h9M4 17l3 3M4 17l3-3" />
                            </svg>
                            <span class="font-medium">Cambiar estado</span>
                        </button>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center">
                        <button data-id="${c.id}" class="reemplazar flex items-center gap-2 p-2 rounded-lg text-sm text-black hover:text-[#7B1FA3] hover:bg-purple-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M23 4v6h-6M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
                            </svg>
                            <span class="font-medium">Reemplazar equipo</span>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    contenedorInformacion.innerHTML = filas;
}

setInterval(()=>{
    if (bandReportes)  consultarReportes(idRep);
},5000);

setInterval(()=>{
    if (bandAuditoria)  consultarAuditoria(idAud);
},5000);