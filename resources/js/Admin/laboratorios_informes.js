"use strict";

const contenedorInformacion = document.getElementById("contenedor-tarjetas");
const buscador = document.getElementById("buscador");
const filtroTipo = document.getElementById("filtrar-tipo");

async function buscadorGeneral(){
    const response = await fetch(`/admin/informes/laboratorios/buscador?texto=${buscador.value}&tipo=${filtroTipo.value}`);
    const data = await response.json();
    
    contenedorInformacion.innerHTML = generarRegistro(data);
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

function generarRegistro(data){

    let fila = '';

    console.log(data);

    data.forEach(laboratorio =>{
        fila += `
            <a href="/admin/informes/laboratorios/${laboratorio.id}-${ (laboratorio.tipo == 'prestamos')?'laboratorio-normal':'laboratorio-computo/computadoras' }" class="flex flex-col gap-2 cursor-pointer">
                <div class="bg-white p-5 rounded-[20px] border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-all h-full">
                    <!-- Tipo de laboratorio -->
                    <div class="mb-4">
                        <span class="bg-[#E0E7FF] text-[#3730A3] text-[10px] font-bold px-3 py-1 rounded-full tracking-wider uppercase">
                            ${laboratorio.tipo}
                        </span>
                    </div>

                    <!-- Nombre -->
                    <h3 class="font-bold text-gray-900 text-base leading-tight">
                        ${laboratorio.nombre}
                    </h3>
                </div>
            </a>
        `;
    });

    return fila;
}

filtroTipo.addEventListener("change", ()=>{
    buscadorGeneral();
});