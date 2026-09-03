"use strict";

import { buscadorGeneral } from "./buscador_computadora";

const contenedorReportes = document.getElementById('contenedor-reportes');
const crear = document.getElementById('enviar');
const buscador = document.getElementById('buscador');
const idLaboratorio = document.getElementById('id-laboratorio').value;
const limiteSolicitudes = parseInt(document.getElementById('limite-solicitudes').value);
let cantidadSolcitudes = parseInt(document.getElementById('cantidad-solicitudes').value);
let numeroComputadora;
let idCom;
let band = false;

document.addEventListener("click", function(e){
    const tarjeta =  e.target.closest('.tarjeta');

    if (tarjeta){
        const id = tarjeta.dataset.id;
        numeroComputadora = tarjeta.dataset.numerocomputadora;
        idCom = id;
        band = true;
        obtenerReportes(id);

        if(window.innerWidth < 1024){
            setTimeout(() => {
                openCart();
            }, 150);
        }
    }
});

function generarTarjetasReportes(informacion){
    contenedorReportes.innerHTML = '';

    document.getElementById("encabezado").innerHTML = `Solicitudes de Reportes - ${numeroComputadora}`;

    let tarjetas = ``;

    informacion.forEach(r =>{
        tarjetas += `
            <div class="p-4 bg-[#F7F6F8] rounded-2xl border-2 border-red-200 relative group hover:shadow-md hover:border-red-600 transition-all cursor-default">
                <p class="text-[11px] text-gray-700 font-bold leading-relaxed line-clamp-3 uppercase">
                    ${r.tipo}
                </p>
                <p class="text-[11px] text-gray-500 font-bold leading-relaxed line-clamp-3">
                    ${r.descripcion}
                </p>
            </div>
        `;
    });

    contenedorReportes.innerHTML = tarjetas;
}

async function obtenerReportes(id){
    const response = await fetch(`/usuario/normal/laboratorios/obtener-reportes-computo?id=${id}`);
    const data = await response.json();

    generarTarjetasReportes(data);
}

crear.addEventListener("click", function(){
    if (document.getElementById('descripcion-reporte').value.trim() === ''){
        alert('No se puede hacer un reporte sin descripcion');
        return;
    }
    
    if (document.getElementById('descripcion-reporte').value.trim().length > 255){
        alert('La descripcion no puede exceder los 255 caracteres');
        return;
    }

    if (!idCom){
        alert('Debes seleccionar un computadora donde realizar el reporte');
        return;
    }

    if (cantidadSolcitudes + 1 > limiteSolicitudes){
        alert('Has alcanzado el limite de reportes permitidos');
        return;
    }

    if (confirm('Deseas realizar el reporte ??')){
        crearSolicitud();
        document.getElementById('tipo').selectedIndex = 0;
        document.getElementById('descripcion-reporte').value = '';
        document.getElementById("encabezado").innerHTML = `Solicitudes de Reportes`;
        contenedorReportes.innerHTML = '';
        band = false;
        idCom = null;
        buscador.value = '';
        buscadorGeneral();
    }
});

async function crearSolicitud(){
    const datos = {
        'id_computadora': idCom,
        'tipo': document.getElementById('tipo').value,
        'descripcion': document.getElementById('descripcion-reporte').value.trim(),
        'id_laboratorio': idLaboratorio
    };

    try{
        const respuesta = await fetch('/usuario/normal/laboratorios/solicitud-computo',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(datos)
        });

        const resultado = await respuesta.json();

        if (respuesta.ok){
            alert("¡Reporte realizado con éxito!");
            cantidadSolcitudes = parseInt(resultado);
            document.getElementById('cantidad-solicitudes').value = cantidadSolcitudes;
        }else{
            alert(resultado.error);
        }
    }catch (error){
        console.error("Error de conexión:", error);
    }
}

setInterval(() => {
    if (band) obtenerReportes(idCom);
}, 5000);