"use strict";

const contenedorSolicitudes = document.getElementById('contenedor-solicitudes');
const buscador = document.getElementById('buscador');
const filtro = document.getElementById('filtro');
const usuario = {
    'id': document.getElementById('id_usuario').value,
    'nombre': document.getElementById('nombre').value,
    'email': document.getElementById('email').value
};

contenedorSolicitudes.addEventListener('click', function(e) {
    const btnAceptar = e.target.closest('.aceptada');
    const btnRechazar = e.target.closest('.rechazada');

    if (btnAceptar) {
        const id = btnAceptar.dataset.id;
        if (confirm("¿Deseas aceptar la solicitud?")) {
            solicitudAceptada(id);
            buscador.value = '';
            filtro.selectedIndex = 0;
            buscadorGeneral();
        }
    }

    if (btnRechazar) {
        const id = btnRechazar.dataset.id;
        const idLab = btnRechazar.dataset.idlaboratorio;
        const idUsuario = btnRechazar.dataset.idusuario;
        const fecha = btnRechazar.dataset.fecha;
        
        if (confirm("¿Deseas rechazar la solicitud?")) {
            rechazarSolicitud(id, idLab, idUsuario, fecha);
            buscador.value = '';
            filtro.selectedIndex = 0;
            buscadorGeneral();
        }
    }
});

async function rechazarSolicitud(id, idLaboratorio, idUsuario, fecha){
    const datos = {
        'id_solicitud': id,
        'id_usuario': idUsuario,
        'id_laboratorio': idLaboratorio,
        'fecha': fecha
    };

    try{
        const respuesta = await fetch(`/usuario/encargado/rechazar-solicitud-prestamos/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(datos)
        });

        const resultado = await respuesta.json();

        if (respuesta.ok){
            alert("Solicitud rechazada.");
        }else{
            alert("Error: " + resultado.message);
        }
    }catch (error){
        console.error("Error en la conexión:", error);
    }
}

async function solicitudAceptada(id){
    const datos = {
        'id_solicitud': id,
        'info_usuario': usuario,
        'estado': 'aceptada'
    };

    try{
        const respuesta = await fetch('/usuario/encargado/actualizar-solicitudes',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(datos)
        });

        const resultado = await respuesta.json();

        if (respuesta.ok){
            alert("Solicitud aceptada correctamente");
        }else{
            alert(resultado.error);
        }
    }catch (error){
        console.error("Error de conexión:", error);
    }
}

function generarRegistro(informacion){
    contenedorSolicitudes.innerHTML = '';

    let registros = '';

    informacion.forEach(s =>{

        const infoUsuario = JSON.parse(s.info_usuario);
        const infoMateriales = JSON.parse(s.info_material);
        const materialesString = JSON.stringify(infoMateriales).replace(/"/g, '&quot;');
        const fechaObj = new Date(s.fecha);
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
                    ${s.id}
                </td>

                <!-- Laboratorio -->
                <td class="px-6 py-4 text-center text-black text-sm font-medium tracking-tight">
                    ${infoUsuario.nombreLaboratorio}
                </td>

                <!-- Lista de Materiales -->
                <td class="px-6 py-4 justify-center">
                    <div class="flex justify-center">
                        <button type="button" onclick="openMaterialModal(${s.id}, ${materialesString})" 
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
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-3">
                        <!-- Aprobar Solicitud -->
                        <button data-id="${s.id}" class="aceptada flex items-center gap-1 px-3 py-1.5 rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all text-xs font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Aprobar
                        </button>

                        <!-- Rechazar Solicitud -->
                        <button data-id="${s.id}" data-idlaboratorio="${infoUsuario.idLaboratorio}" data-idusuario="${infoUsuario.id}" data-fecha="${s.fecha}" class="rechazada flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all text-xs font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Rechazar
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    contenedorSolicitudes.innerHTML = registros;
}

async function buscadorGeneral(){
    const response = await fetch(`/api/usuario/encargado/solicitudes-pendientes?texto=${buscador.value}&filtro=${filtro.value}`);
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

filtro.addEventListener("change", ()=>{
    buscadorGeneral();
});

setInterval(() => {
    buscadorGeneral();
}, 5000);