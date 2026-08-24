"use strict";

const usuario = {
    'id': document.getElementById('id_usuario').value,
    'nombre': document.getElementById('nombre').value,
    'email': document.getElementById('email').value
};
const contenedorReportes = document.getElementById('contenedor-reportes');

document.addEventListener('click', (e)=>{
    const cambiar = e.target.closest('.cambiar');
    
    if (cambiar){
        if (confirm('Deseas cambiar el estado del reporte ??')){
            const id = cambiar.dataset.id;
            const estado = cambiar.dataset.estado;

            cambiarEstado(id, estado);
            actualizarInformacion();
        }
    }

    const reportar = e.target.closest('.reportar');

    if (reportar){
        if (confirm('La computadora no funciona ??')){
            const id = reportar.dataset.id;
            const idSolicitud = reportar.dataset.idsolicitud;


            editarEstado(id, idSolicitud);
            actualizarInformacion();
        }
    }
});

async function cambiarEstado(id, estado){
    const datos = {
        'id_solicitud': id,
        'estado': estado,
        'info_usuario': usuario
    };

    try{
        const respuesta = await fetch('/usuario/mantenimiento/actualizar-solicitudes-computo',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(datos)
        });

        const resultado = await respuesta.json();

        if (respuesta.ok){
            alert("Reporte actualizado correctamente");
        }else{
            alert(resultado.error);
        }
    }catch (error){
        console.error("Error de conexión:", error);
    }
}

async function actualizarInformacion(){
    const response = await fetch(`/usuario/mantenimiento/actualizar-informacion-reportes`);
    const data = await response.json();

    generarRegistro(data);
}

function generarRegistro(informacion){
    contenedorReportes.innerHTML = '';

    let reportes = '';

    informacion.forEach(r =>{
        const fechaObj = new Date(r.fecha);
        const fechaFormateada = fechaObj.toLocaleDateString('es-ES', {
            day: '2-digit', month: '2-digit', year: 'numeric'
        });

        reportes += `
            <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-4">
                    ${r.numero_computadora}
                </td>

                <!-- Material Dañado -->
                <td class="px-6 py-4 text-center text-black text-sm font-medium tracking-tight">
                        ${r.nombre}
                </td>

                <td class="px-6 py-4 text-sm text-black uppercase text-center font-medium">
                    ${r.tipo}
                </td>

                <!-- Descripcion -->
                <td class="px-6 py-4 justify-center">
                    <div class="flex justify-center">
                        <button type="button" onclick="openMaterialModal('${r.id}', '${r.descripcion}')" 
                            class="flex items-center gap-2 text-[#7B1FA3]" title="Ver Reporte">
                            <div class="p-1.5 bg-purple-100 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </td>

                <!-- Fecha -->
                <td class="px-6 py-4 text-sm text-gray-500 text-center">
                    ${fechaFormateada}
                </td>

                <!-- Estado del Reporte -->
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center">
                        <span class="px-2 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-lg border border-green-100 uppercase">
                            ${r.estado}
                        </span>
                    </div>
                </td>

                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center">
                        <span class="px-2 py-1 bg-orange-50 text-orange-700 text-xs font-bold rounded-lg border border-orange-100 uppercase">
                            ${ (r.estado == 'aceptada' || r.estado == 'reprogramado')?'en proceso':'reparado' }
                        </span>

                        <button data-estado="${ (r.estado == 'aceptada' || r.estado == 'reprogramado')?'en proceso':'reparado' }" data-id="${r.id}"
                            class="cambiar p-2 bg-[#7B1FA3] text-white rounded-xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]"
                            title="Guardar cambio">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
        `;

        if (r.estado != 'aceptada' && r.estado != 'reprogramado'){
            reportes += `
                    <div class="flex justify-center">
                        <button data-idsolicitud="${r.id}" data-id="${r.id_computadora}" class="reportar flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all text-xs font-bold">
                            Reportar
                        </button>
                    </div>
            `;
        }

        reportes += `
                </td>
            </tr>
        `;
    });

    contenedorReportes.innerHTML =  reportes;
}

async function editarEstado(id, idSolicitud){
    const datos = {
        'estado': 'sin reparacion',
        'info_usuario': usuario,
        'id_solicitud': idSolicitud
    };

    try{
        const respuesta = await fetch(`/usuario/matenimiento/editar-computadora-${id}`,{
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

setInterval(() => {
    actualizarInformacion();
}, 5000);