"use strict";

const buscador = document.getElementById('buscador');
buscador.placeholder = "ID de Solicitud o No Computadora";
const filtro = document.getElementById('filtro');
const filtroTipo = document.getElementById('filtro-tipo');
const contenedorReportes =  document.getElementById('contenedor-reportes');
const usuario = {
    'id': document.getElementById('id_usuario').value,
    'nombre': document.getElementById('nombre').value,
    'email': document.getElementById('email').value
};

async function buscadorGeneral(){
    const response = await fetch(`/api/usuario/encargado/solicitudes-aceptadas-computo?texto=${buscador.value}&filtro=${filtro.value}&filtrotipo=${filtroTipo.value}`);
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

filtro.addEventListener("change", ()=>{
    buscadorGeneral();
});

filtroTipo.addEventListener("change", ()=>{
    buscadorGeneral();
});

function generarRegistros(informacion){
    contenedorReportes.innerHTML = '';

    let registros = '';

    informacion.forEach(r =>{
        const fechaObj = new Date(r.fecha);
        const fechaFormateada = fechaObj.toLocaleDateString('es-ES', {
            day: '2-digit', month: '2-digit', year: 'numeric'
        });

        registros += `
            <tr class="hover:bg-gray-50/50 transition-colors group">
                <!-- ID de la Solicitud -->
                <td class="px-6 py-4 text-sm text-black text-center font-medium">
                    ${r.id}
                </td>

                <!-- Numero de computadora -->
                <td class="px-6 py-4 text-sm text-black text-center font-medium">
                    ${r.numero_computadora}
                </td>

                <!-- Laboratorio -->
                <td class="px-6 py-4 text-center text-black text-sm font-medium tracking-tight">
                        ${r.nombre}
                </td>

                <td class="px-6 py-4 text-black text-sm font-medium text-center uppercase">
                    ${r.tipo}
                </td>

                <!-- Descripcion -->
                <td class="px-6 py-4 justify-center">
                    <div class="flex justify-center">
                        <button type="button" 
                            onclick="openMaterialModal('${r.id}', '${r.descripcion}')" 
                            class="flex items-center gap-2 text-[#7B1FA3] hover:text-white"
                            title="Ver motivo">
                            <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
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
        `;

        if (r.estado == 'aceptada' || r.estado == 'en proceso' || r.estado == 'reprogramado'){
            registros += `
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center">
                            <span class="px-3 py-1 text-[10px] bg-orange-50 text-orange-600 font-bold rounded-lg border border-orange-100 uppercase">
                                ${r.estado}
                            </span>
                        </div>
                    </td>
                    <td>
                    </td>
                </tr>
            `;
        }

        if (r.estado == 'reparado'){
            registros += `
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center">
                            <span class="px-3 py-1 text-[10px] bg-orange-50 text-orange-600 font-bold rounded-lg border border-orange-100 uppercase">
                                ${r.estado}
                            </span>

                            <button data-id='${r.id}' data-estado='completado'
                                class="completar px-3 py-1 bg-[#7B1FA3] text-white rounded-xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98] ml-2"
                                title="Guardar cambio">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </div>
                    </td>

                    <td>
                        <div class="flex justify-center">
                            <button data-id='${r.id}' data-estado='reprogramado' class="reportar flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all text-xs font-bold">
                                Reportar
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }
    });

    contenedorReportes.innerHTML = registros;
}

document.addEventListener('click', (e)=>{
    const completar = e.target.closest('.completar');

    if (completar){
        if (confirm('El reporte ha sido completado ??')){
            const id = completar.dataset.id;
            const estado = completar.dataset.estado;

            cambiarEstado(id, estado);
            buscador.value = '';
            filtro.selectedIndex = 0;
            buscadorGeneral();
        }
    }

    const reprogramar = e.target.closest('.reportar');

    if (reprogramar){
        if (confirm('El reporte no ha sido completado aun ??')){
            const id = reprogramar.dataset.id;
            const estado = reprogramar.dataset.estado;

            cambiarEstado(id, estado);
            buscador.value = '';
            filtro.selectedIndex = 0;
            buscadorGeneral();
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
        const respuesta = await fetch('/usuario/encargado/actualizar-solicitudes-computo',{
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

setInterval(() => {
    buscadorGeneral();
}, 5000);