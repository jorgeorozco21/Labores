"use strict";

const contenedorMateriales = document.getElementById('contenedor-materiales-solicitar');
const contenedorSolicitudes = document.getElementById('contenedor-solicitudes');
const botonEliminar = document.getElementById('eliminar-solicitud');
const idLaboratorio = document.getElementById('idLaboratorio').value;
let idSol;
let band = false;

document.addEventListener("click", (e)=>{
    
    const tarjeta = e.target.closest(".solicitud");

    if (tarjeta) {

        const idSolicitud = tarjeta.dataset.id;
        const estado = tarjeta.dataset.estado;
        idSol = idSolicitud;
        band = true;

        if (!estado || estado === "null" || estado === ""){
            botonEliminar.dataset.ideliminar = idSolicitud;
            botonEliminar.classList.remove("bg-gray-400")
            botonEliminar.classList.add("bg-purple-700",'hover:bg-[#7B1FA3]');
            botonEliminar.disabled = false;
        }else{
            delete botonEliminar.dataset.ideliminar;
            botonEliminar.classList.remove("bg-purple-700","hover:bg-[#7B1FA3]");
            botonEliminar.classList.add("bg-gray-400");
            botonEliminar.disabled = true;
        } 

        openCart();
        infoSolicitud(idSolicitud);
    }

    const eliminar = e.target.closest(".solicitud-eliminada");

    if (eliminar){

        const idSolicitudEliminada = eliminar.dataset.id;

        eliminarSolicitudEliminada(idSolicitudEliminada);

        actualizarSolicitudes();
    }
});

async function infoSolicitud(id){
    const response = await fetch(`/usuario/normal/laboratorio/informacion-solicitud?id=${id}`);
    const data = await response.json();

    generarTarjetas(data.info_material);
}

function generarTarjetas(informacion){
    contenedorMateriales.innerHTML = "";

    let tarjetas = "";

    informacion = JSON.parse(informacion);

    //console.log(informacion);

    informacion.forEach(m => {
        tarjetas += `
            <div class="p-4 bg-[#F7F6F8] rounded-2xl border border-gray-100 relative group hover:shadow-md hover:border-gray-200 transition-all cursor-default">
                <p class="text-sm font-bold text-gray-800 pr-6 mb-3">${m.nombre}</p>
                <div class="inline-flex items-center justify-center w-10 h-10 bg-white border border-gray-200 rounded-xl shadow-sm">
                    <span class="text-sm font-bold text-gray-700">${m.cantidad}</span>
                </div>
            </div>
        `;
    });

    contenedorMateriales.innerHTML = tarjetas;
}

const cart = document.getElementById('cart');
const overlay = document.getElementById('overlay');

function openCart() {
    cart.classList.remove('translate-y-full');
    overlay.classList.remove('opacity-0', 'pointer-events-none');
}

function closeCart() {
    contenedorMateriales.innerHTML = '';
    cart.classList.add('translate-y-full');
    overlay.classList.add('opacity-0', 'pointer-events-none');
}

overlay.addEventListener('click', closeCart);

botonEliminar.addEventListener("click", ()=>{
    const confirmar = confirm("Deseas cancelar la solicitud ??");

    if (confirmar){
        const id = botonEliminar.dataset.ideliminar;
        contenedorMateriales.innerHTML = '';
        delete botonEliminar.dataset.ideliminar;
        eliminarSolicitud(id);
        actualizarSolicitudes();
    }
});

async function eliminarSolicitud(id){
    try{
        const respuesta = await fetch(`/usuario/normal/eliminar-solicitud/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        const resultado = await respuesta.json();

        if (respuesta.ok){
            alert("Solicitud cancelada.");
        }else{
            alert("Error: " + resultado.message);
        }
    }catch (error){
        console.error("Error en la conexión:", error);
    }
}

async function eliminarSolicitudEliminada(id){
    try{
        const respuesta = await fetch(`/usuario/normal/eliminar-solicitud-eliminada/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
    }catch (error){
        console.error("Error en la conexión:", error);
    }
}

async function actualizarSolicitudes(){
    const response = await fetch(`/usuario/normal/actualizar-solicitudes?id=${idLaboratorio}`);
    const data = await response.json();

    generarTarjetasSolicitudes(data);
}

function generarTarjetasSolicitudes(informacion){
    contenedorSolicitudes.innerHTML = "";

    let tarjetas = "";

    informacion.solicitudes_eliminadas.forEach(s =>{
        tarjetas += `
            <div data-id="${s.id}" class="solicitud-eliminada bg-white p-6 rounded-[20px] border border-gray-100 shadow-sm flex w-full hover:shadow-md hover:border-2 transition-shadow cursor-pointer">
                <div class="space-y-2 w-full">
                    <div class="flex justify-between w-full">
                        <h2 class="text-lg font-extrabold text-[#1e293b] tracking-tight">
                            Solicitud de Materiales
                        </h2>
                    </div>
                    <div class="space-y-1 flex justify-between items-center">
                        <div>
                            <!-- ID de la Solicitud -->
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                <span class="text-gray-600">ID:</span> ${s.id_solicitud}
                            </p>
                            <!-- Fecha de la Solicitud -->
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                <span class="text-gray-600">Fecha:</span> ${s.fecha}
                            </p>
                        </div>
                        <div>
                            <div class="w-12 h-12 flex items-center justify-center bg-red-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    informacion.solicitudes.forEach(s => {
        tarjetas += `
            <div data-id="${s.id}" data-estado="${s.estado}" class="solicitud bg-white p-6 rounded-[20px] border border-gray-100 shadow-sm flex w-full hover:shadow-md hover:border-[#7B1FA3] hover:border-2 transition-shadow cursor-pointer">
                <div class="space-y-2 w-full">
                    <div class="flex justify-between w-full">
                        <h2 class="text-lg font-extrabold text-[#1e293b] tracking-tight">
                            Solicitud de Materiales
                        </h2>
                    </div>
                    <div class="space-y-1 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                <span class="text-gray-600">ID:</span> ${s.id }
                            </p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                <span class="text-gray-600">Fecha:</span> ${s.fecha}
                            </p>
                        </div>
                        <div>
        `;

        if (s.estado == null){
            tarjetas += `
                            <div class="w-12 h-12 flex items-center justify-center bg-amber-50 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
            `;
        }else if (s.estado == 'aceptada'){
            tarjetas += `
                            <div class="w-12 h-12 flex items-center justify-center bg-green-50 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
            `;
        }else if (s.estado == 'en prestamo'){
            tarjetas += `
                            <div class="w-12 h-12 flex items-center justify-center bg-green-50 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
            `;
        }

        tarjetas += `
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    contenedorSolicitudes.innerHTML = tarjetas;
}

setInterval(() => {
    actualizarSolicitudes();
}, 5000);

setInterval(() => {
    if (band) infoSolicitud(idSol);
}, 5000);