"use strict";

const contenedorSolicitudes = document.getElementById('contenedor-solicitudes');
const buscador = document.getElementById('buscador');
const filtro = document.getElementById('filtro');
const usuario = {
    'id': document.getElementById('id_usuario').value,
    'nombre': document.getElementById('nombre').value,
    'email': document.getElementById('email').value
};

document.addEventListener('click', function(e){
    const btnCambiar = e.target.closest('.cambiar');

    if (btnCambiar){
        const id = btnCambiar.dataset.id;
        const estado = btnCambiar.dataset.estado;

        console.log(id);

        if (estado == "recibido"){
            consultarInformacion(id, "completar");
            return;
        }

        if (confirm(`Deseas cambiar el estado de la solicitud ??`)){
            cambiarEstadoSolicitud(id, estado);
            buscador.value = '';
            filtro.selectedIndex = 0;
            buscadorGeneral();
        }
    }

});

async function consultarInformacionSolicitudCompletar(id){
    const response = await fetch(`/api/info-solictud-completar?id=${id}`);
    const data = await response.json();

    return data;
}

function modalCompletar(id ,materiales){
    const contenedorLista = document.getElementById('material-lista-completar');
    const idLabel = document.getElementById('id-solicitud-completar');

    contenedorLista.innerHTML = '';
    idLabel.innerText = '#' + id;

    materiales.forEach(m =>{
        const li = document.createElement('li');
        li.className = "group mb-2.5 bg-white hover:bg-[#F5F3FF] border-l-4 border-[#7B1FA3] has-[:checked]:border-l-transparent rounded-r-xl border-gray-100 shadow-sm hover:shadow-md transition-all duration-200";
        li.innerHTML = `
            <label class="flex items-center justify-between p-3.5 cursor-pointer w-full">
                <div class="flex items-center gap-3.5 min-w-0 flex-1 pr-2">
                    <input type="checkbox" value="${m.id}" class="entregado peer hidden"> 

                    <div class="w-5 h-5 border-2 border-gray-300 rounded-md flex items-center justify-center peer-checked:bg-[#7B1FA3] peer-checked:border-[#7B1FA3] transition-all shrink-0">
                        <svg class="w-3 h-3 text-white peer-checked:scale-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-gray-800 group-hover:text-[#7B1FA3] peer-checked:line-through peer-checked:text-gray-400 transition-colors truncate">
                            ${m.nombre}
                        </p>
                    </div>
                </div>

                <span class="shrink-0 ml-2 px-2.5 py-1 bg-purple-50 group-hover:bg-purple-100/70 text-[#7B1FA3] text-xs font-bold rounded-lg border border-purple-100 transition-colors">
                    ${m.cantidad}
                </span>
            </label>
        `;

        contenedorLista.appendChild(li);
    });

    document.getElementById("id-solicitud-completar").value = id;

    document.getElementById("material-modal-completar").classList.remove("hidden");
}

function cerrarModalCompletar(){
    document.getElementById("notas").value = "";
    document.getElementById("id-solicitud-completar").value = "";
    document.getElementById("notas").classList.add("hidden");
    document.getElementById("label-notas").classList.add("hidden");
    document.getElementById("material-modal-completar").classList.add("hidden");
}

document.getElementById("fondo-modal-completar").addEventListener("click", cerrarModalCompletar);
document.getElementById("boton-cerrar-modal-completar").addEventListener("click", cerrarModalCompletar);

document.getElementById("boton-completar").addEventListener("click", ()=>{
    if (confirm('Deseas cambiar el estado del historial ??')){
        const checkboxs = document.querySelectorAll(".entregado:checked");
        const seleccionados = [];

        checkboxs.forEach(c =>{
            seleccionados.push(c.value);
        });

        if (checkboxs.length == document.querySelectorAll(".entregado").length){
            crearHistorial(seleccionados);
            return;
        }

        if (!document.getElementById("notas").classList.contains("hidden") && notas.value.trim() == ""){
            alert("Tienes que indicar porque no todos los materiales estan marcados. En caso de presionar por error marca todos los materiales y vuelve a presionar el boton sin colocar notas.");
            return;
        }

        if (!document.getElementById("notas").classList.contains("hidden") && notas.value.trim() != ""){
            crearHistorial(seleccionados);
            return;
        }

        if (checkboxs.length != document.querySelectorAll(".entregado").length){
            document.getElementById("notas").classList.remove("hidden");
            document.getElementById("label-notas").classList.remove("hidden");
            return;
        }
    }
});

async function crearHistorial(seleccionados){
    const informacion = await consultarInformacionSolicitudCompletar(document.getElementById("id-solicitud-completar").value);
    const informacionUsuario = JSON.parse(informacion.info_usuario);
    const datos = {
        'id_solicitud': document.getElementById("id-solicitud-completar").value,
        'info_usuario' : informacionUsuario,
        'info_auditoria' : usuario,
        'seleccionados' : seleccionados,
        'notas' : document.getElementById("notas").value,
        'estado' : 'recibido'
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
            cerrarModalCompletar();
            buscador.value = '';
            filtro.selectedIndex = 0;
            buscadorGeneral();
            alert("Solicitud actualizada correctamente");
        }else{
            alert(resultado.error);
        }
    }catch (error){
        console.error("Error de conexión:", error);
    }
}

async function cambiarEstadoSolicitud(id, estado){
    const datos = {
        'id_solicitud': id,
        'info_usuario': usuario,
        'estado': estado
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
            alert("Solicitud actualizada correctamente");
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
                <td class="px-6 py-4">
                    <!-- Nombre, Correo y Grado/Grupo -->
                    <div class="flex items-center gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-800 truncate">${infoUsuario.nombre}</p>
                            <p class="text-[10px] text-gray-400 font-medium">${infoUsuario.email}</p>
                            <p class="text-[10px] text-gray-400 font-medium">${infoUsuario.grado}° ${infoUsuario.grupo} - ${infoUsuario.nombreGrupo} - ${infoUsuario.turno}</p>
                        </div>
                    </div>
                </td>

                <!-- ID de la Solicitud -->
                <td class="px-6 py-4 text-sm text-black text-center font-medium">
                    ${s.id}
                </td>

                <!-- Laboratorio -->
                <td class="px-6 py-4 text-center text-black text-sm font-medium tracking-tight">
                    ${infoUsuario.nombreLaboratorio}
                </td>

                <!-- Lista de Materiales-->
                <td class="px-6 py-4">
                    <div class="flex justify-center">
                        <button type="button" onclick="openMaterialModal(${s.id},${materialesString})" 
                            class="flex items-center gap-2 text-[#7B1FA3] hover:text-white">
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

                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center">
                        <span class="px-3 py-1 text-[10px] bg-green-50 text-green-600 font-bold rounded-lg border border-green-100 uppercase">
                            ${s.estado}
                        </span>
                    </div>
                </td>

                <!-- Estado de la Solicitud -->
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center">
                        <!-- Select de Estados -->
                        <span class="px-3 py-1 text-[10px] bg-orange-50 text-orange-600 font-bold rounded-lg border border-orange-100 uppercase">
                            ${ (s.estado == 'aceptada') ? 'En Prestamo' : 'Recibido' }
                        </span>

                        <!-- Boton de Guardar -->
                        <button type="submit" data-estado="${ (s.estado == 'aceptada') ? 'en prestamo' : 'recibido' }" data-id="${s.id}"
                            class="cambiar px-3 py-1 bg-[#7B1FA3] text-white rounded-xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98] ml-2"
                            title="Guardar cambio">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    contenedorSolicitudes.innerHTML = registros;
}

async function buscadorGeneral(){
    const response = await fetch(`/api/usuario/encargado/solicitudes-aceptadas?texto=${buscador.value}&filtro=${filtro.value}`);
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

const abrir = document.getElementById("abrir-modal");
const cerrar = document.getElementById("cerrar-modal-reporte");
const buscar = document.getElementById("boton-buscar");
const seleccionar = document.getElementById("boton-seleccionar");
const generarReporte = document.getElementById("enviar-reporte");
let materiales;
let actual = 1;
let cantidadMaxima;
let idSolicitud;
let idInventario;

abrir.addEventListener('click', function() {
    consultarSolicitudes();
});

async function consultarSolicitudes(){
    const response = await fetch('/api/solicitudes-en-prestamo');
    const data = await response.json();

    const select = document.getElementById('opciones-solicitudes');
    select.innerHTML = '';

    data.forEach(s =>{
        select.innerHTML += `
            <option value="${s.id}">${s.id}</option>
        `;
    });

    const modal =  document.getElementById('material-modal-reporte');

    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function reiniciarFormulario(){
    actual = 1;
    document.getElementById('mas').classList.add('hidden');
    document.getElementById('menos').classList.add('hidden');
    document.getElementById('cantidad').innerHTML = '';
    document.getElementById('cantidad-reportar').innerHTML = '';
    document.getElementById('descripcion').value = '';
    document.getElementById('descripcion').disabled = true;
    document.getElementById('opciones-solicitudes').innerHTML = '';
    document.getElementById('opciones-materiales-reportar').disabled = true;
    document.getElementById('opciones-materiales-reportar').innerHTML = '';
    generarReporte.disabled = true;

    const modal = document.getElementById('material-modal-reporte');

    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

cerrar.addEventListener('click', function (){
    reiniciarFormulario();
});

buscar.addEventListener('click', function (){
    consultarInformacion(document.getElementById('opciones-solicitudes').value, "reporte");
});

async function consultarInformacion(id, tipo){
    const response = await fetch(`/api/info-materiales-solicitud-prestamo?id=${id}`);
    const data = await response.json();

    idSolicitud = id;
    materiales = JSON.parse(data.info_material);

    if (tipo == "completar"){
        modalCompletar(id, materiales);
        return;
    }

    const opcionesMateriales = document.getElementById('opciones-materiales-reportar');

    opcionesMateriales.innerHTML = '';

    materiales.forEach(m =>{
        opcionesMateriales.innerHTML += `
            <option value="${m.nombre}">${m.nombre}</option>
        `;
    })

    opcionesMateriales.disabled = false;
}

seleccionar.addEventListener('click', function (){
    const info = materiales.find(m => m.nombre == document.getElementById('opciones-materiales-reportar').value);
    cantidadMaxima = info.cantidad;
    idInventario = info.id;
    if (cantidadMaxima == 1){
        document.getElementById('mas').classList.add('hidden');
        document.getElementById('menos').classList.add('hidden');
    }else{
        document.getElementById('mas').classList.remove('hidden');
        document.getElementById('menos').classList.remove('hidden');
    }
    document.getElementById('cantidad-reportar').innerHTML = ` 1 `;
    actual = 1;
    document.getElementById('cantidad').innerHTML = ` ${cantidadMaxima} `;
    document.getElementById('descripcion').disabled = false;
    document.getElementById('descripcion').value = '';
    generarReporte.disabled = false;
});

document.getElementById('mas').addEventListener('click', function (){
    if (actual < cantidadMaxima){
        actual++;
        document.getElementById('cantidad-reportar').innerHTML = ` ${actual} `
    }
});

document.getElementById('menos').addEventListener('click', function (){
    if (actual > 1){
        actual--;
        document.getElementById('cantidad-reportar').innerHTML = ` ${actual} `
    }
});

generarReporte.addEventListener('click', function (){
    if (document.getElementById('descripcion').value == ''){
        alert('No puedes generar un reporte sin descripcion');
    }else{
        if (confirm('Deseas generar el reporte ??')){
            reporte();
            buscador.value = '';
            filtro.selectedIndex = 0;
            buscadorGeneral();
            reiniciarFormulario();
        }
    }
});

async function reporte(){
    const material = document.getElementById('opciones-materiales-reportar').value;
    const indice = materiales.findIndex(m => m.nombre == material);

    materiales[indice].cantidad = cantidadMaxima - actual;

    materiales = materiales.filter(m => m.cantidad > 0);

    const datos = {
        'id': idSolicitud,
        'info_usuario': usuario,
        'info_material': materiales,
        'id_inventario': idInventario,
        'descripcion': document.getElementById('descripcion').value,
        'cantidad': actual
    };

    try{
        const respuesta = await fetch('/creacion-reporte-material',{
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
            alert("Reporte generado correctamente");
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