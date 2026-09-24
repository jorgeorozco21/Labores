"use strict";

const buscador = document.getElementById('buscador');
const filtro = document.getElementById('filtro');
const contenedorHistoriales =  document.getElementById('contenedor-historiales');
const usuario = {
    'id': document.getElementById('id_usuario').value,
    'nombre': document.getElementById('nombre').value,
    'email': document.getElementById('email').value
};
const limiteBloqueo = document.getElementById("bloqueo").value;

async function buscadorGeneral(){
    const response = await fetch(`/api/usuario/encargado/historiales?texto=${buscador.value}&filtro=${filtro.value}`);
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

function generarRegistros(informacion){
    contenedorHistoriales.innerHTML = '';

    let registros = '';

    informacion.forEach(h =>{

        const infoUsuario = JSON.parse(h.info_usuario);
        const infoMateriales = JSON.parse(h.info_material);
        const materialesString = JSON.stringify(infoMateriales).replace(/"/g, '&quot;');
        const fechaObj = new Date(h.created_at);
        const fechaFormateada = fechaObj.toLocaleDateString('es-ES', {
            day: '2-digit', month: '2-digit', year: 'numeric'
        });

        registros += `
            <tr class="hover:bg-gray-50/50 transition-colors group">
                <!-- Nombre, Correo y Grado/Grupo -->
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-800 truncate">${infoUsuario.nombre}</p>
                            <p class="text-[10px] text-gray-400 font-medium">${infoUsuario.email}</p>
                            <p class="text-[10px] text-gray-400 font-medium">${infoUsuario.grado}° ${infoUsuario.grupo} - ${infoUsuario.nombreGrupo} - ${infoUsuario.turno}</p>
                        </div>
                    </div>
                </td>

                <!-- ID de Solicitud -->
                <td class="px-6 py-4 text-sm text-black text-center font-medium">
                    ${h.id}
                </td>

                <td class="px-6 py-4 text-center whitespace-nowrap">
                    <div class="flex justify-center">
                        <span class="inline-flex items-center justify-center p-1.5 max-w-[28px] max-h-[28px] text-xs font-bold rounded-lg transition-colors ${(h.total_usuario_pendientes >= limiteBloqueo && limiteBloqueo != -1) ? 'bg-red-100 text-red-600' : 'bg-purple-100 text-[#7B1FA3]'}">
                            ${h.total_usuario_pendientes}
                        </span>
                    </div>
                </td>

                <!-- Laboratorio -->
                <td class="px-6 py-4 text-center text-black text-sm font-medium tracking-tight">
                    ${infoUsuario.nombreLaboratorio}
                </td>

                <!-- Lista de Materiales -->
                <td class="px-6 py-4 justify-center">
                    <div class="flex justify-center">
                        <button type="button" onclick="openMaterialModal(${h.id}, ${materialesString})" 
                            class="flex items-center gap-2 text-[#7B1FA3] hover:text-white transition-colors">
                            <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </td>

                <!-- Fecha -->
                <td class="px-6 py-4 text-sm text-gray-500 text-center">
                    ${fechaFormateada}
                </td>

                <!-- Acciones -->
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center">
                        <!-- Boton de Guardar -->
                        <button data-estado="recibido" data-id="${h.id }"
                            class="cambiar px-3 py-1 bg-[#7B1FA3] text-white rounded-xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98] ml-2"
                            title="Guardar cambio">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    contenedorHistoriales.innerHTML = registros;
}

document.addEventListener("click", (e)=>{
    const cambiar = e.target.closest(".cambiar");

    if (cambiar){
        if (confirm('Deseas quitar este historial ??')){
            const id = cambiar.dataset.id;

            cambiarEstadoSolicitud(id);
        }
    }
});

async function cambiarEstadoSolicitud(id){
    const datos = {
        'id_solicitud': id,
        'info_auditoria': usuario
    };

    try{
        const respuesta = await fetch('/actualizar-historial-alumno',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(datos)
        });

        const resultado = await respuesta.json();

        if (respuesta.ok){
            alert("Historial actualizado correctamente");
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