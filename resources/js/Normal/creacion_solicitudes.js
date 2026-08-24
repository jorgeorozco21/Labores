"use strict";

import { buscadorGeneral } from "./buscador_materiales";

const contenedorMateriales = document.getElementById('contenedor-materiales-solicitar');
const botonEnviar = document.getElementById('enviar');
const buscador = document.getElementById('buscador');
const idLaboratorio = document.getElementById('id-laboratorio').value;
const materialesSolicitados = {};
const materiales = {};
const usuario = {
    'id': document.getElementById('id_usuario').value,
    'nombre': document.getElementById('nombre_usuario').value,
    'email': document.getElementById('email').value,
    'grado': document.getElementById('grado').value,
    'grupo': document.getElementById('grupo').value,
    'nombreGrupo': document.getElementById('nombreGrupo').value,
    'turno': document.getElementById('turno').value,
    'idLaboratorio': idLaboratorio,
    'nombreLaboratorio': document.getElementById('nombreLaboratorio').value
};

document.addEventListener('click', function(e){
    if (e.target.closest(".tarjeta-material")){

        if (e.target.dataset.id == undefined) return;
        
        let id = e.target.dataset.id;
        let nombreMaterial = e.target.dataset.nombre;
        let tipo = e.target.dataset.tipo;
        let cantidadDisponible = e.target.dataset.cantidaddisponible;

        if (!(id in materiales)){
            materiales[id] = {
                'id': id,
                'nombre': nombreMaterial,
                'tipo': tipo,
                'cantidad': 1,
                'cantidad_maxima': cantidadDisponible
            };
        }

        generarTarjeta(materiales);

        if (window.innerWidth < 1024) {
            setTimeout(() => {
                openCart();
            }, 150);
        }
    }

    const botonEliminar = e.target.closest(".eliminar-material");

    if (botonEliminar) {
        const idEliminar = botonEliminar.dataset.ideliminar;
        
        console.log("ID a eliminar:", idEliminar);

        delete materiales[idEliminar];
        delete materialesSolicitados[idEliminar];

        generarTarjeta(materiales);
    }

    if (e.target.closest(".operacion")){

        let id = e.target.dataset.idsum;
        let operacion = e.target.dataset.op;

        if (operacion == "sum"){
            if (materiales[id].cantidad < materiales[id].cantidad_maxima) materiales[id].cantidad++;
        }else{
            if (materiales[id].cantidad > 1) materiales[id].cantidad--;
        }

        generarTarjeta(materiales);
    }
});

function generarTarjeta(informacion){
    contenedorMateriales.innerHTML = "";

    let tarjetas = "";

    for (let m in informacion){
        if (materiales[m].tipo == 'prestamos por unidad'){
            tarjetas += `
                <div class="p-4 bg-[#F7F6F8] rounded-2xl border border-gray-100 relative group hover:shadow-md hover:border-gray-200 transition-all cursor-default">
                    <button data-ideliminar="${materiales[m].id}" class="eliminar-material absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <p class="text-sm font-bold text-gray-800 pr-6 mb-3">${materiales[m].nombre}</p>
                    <div class="inline-flex items-center justify-center w-10 h-10 bg-white border border-gray-200 rounded-xl shadow-sm">
                        <span class="text-sm font-bold text-gray-700">${materiales[m].cantidad}</span>
                    </div>
                </div>
            `;
        }else{
            tarjetas += `
                <div class="p-4 bg-[#F7F6F8] rounded-2xl border border-gray-100 relative group hover:shadow-md hover:border-gray-200 transition-all cursor-default">
                    <button data-ideliminar="${materiales[m].id}" class="eliminar-material absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <p class="text-sm font-bold text-gray-800 pr-6 mb-3">${materiales[m].nombre}</p>
                    <div class="flex items-center bg-white border border-gray-200 rounded-xl w-fit shadow-sm overflow-hidden">
                        <button data-idsum="${materiales[m].id}" data-op="res" class="operacion px-3 py-1.5 text-gray-400 hover:bg-gray-50 border-r transition-colors">-</button>
                        <span class="px-5 py-1.5 text-sm font-bold text-gray-700">${materiales[m].cantidad}</span>
                        <button data-idsum="${materiales[m].id}" data-op="sum" class="operacion px-3 py-1.5 text-gray-400 hover:bg-gray-50 border-l transition-colors">+</button>
                    </div>
                </div>
            `;
        }
    }

    contenedorMateriales.innerHTML = tarjetas;
}

botonEnviar.addEventListener("click", (e)=>{
    e.preventDefault();

    if (Object.keys(materiales).length == 0) alert('No puedes realizar una solicitud vacia');
    else{
        const confirmar =  confirm("Deseas hacer la solicitud ??");

        if (confirmar){
            const infoEnviar = Object.values(materiales).map(m => {
                return {
                    id: m.id,
                    nombre: m.nombre,
                    cantidad: m.cantidad
                };
            });

            crearSolicitud(infoEnviar);

            contenedorMateriales.innerHTML = "";

            for (let m in materiales){
                delete materiales[m];
            }

            buscador.value = '';

            buscadorGeneral(buscador.value, idLaboratorio);
        }
    }
});


async function crearSolicitud(info){
    const datos = {
        'info_usuario': usuario,
        'info_material': info,
        'fecha': new Date().toISOString().slice(0, 19).replace('T', ' ')
    };

    try{
        const respuesta = await fetch('/usuario/normal/crear-solicitud',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(datos)
        });

        const resultado = await respuesta.json();

        if (respuesta.ok){

            alert("¡Solicitud guardada con éxito!");
            
        }else{
            alert(resultado.error);
        }
    }catch (error){
        console.error("Error de conexión:", error);
    }
}

async function consultarMateriales(){
    const response = await fetch(`/usuario/normal/materiales?texto=${buscador.value}&idLab=${idLaboratorio}`);
    const data = await response.json();

    data.forEach(m =>{
        if (m.id in materiales){
            materiales[m.id] = {
                'id': m.id,
                'nombre': m.nombre,
                'tipo': m.tipo,
                'cantidad': Math.min(materiales[m.id].cantidad, m.cantidad_disponible),
                'cantidad_maxima': m.cantidad_disponible
            };
        }
    });

    generarTarjeta(materiales);
}

setInterval(()=>{
    consultarMateriales();
},5000);