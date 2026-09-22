"use strict";

const cerrarModal = document.getElementById("cerrar-modal");
const cerrarModalEdit = document.getElementById("cerrar-modal-edit");

document.addEventListener("click", (e)=>{
    const modalServicios =  e.target.closest(".servicios");

    if (modalServicios){
        const idInstitucion = modalServicios.dataset.id;
        document.getElementById("modal-servicios").classList.remove("hidden");

        consultarServicios(idInstitucion);
    }

    const modalEdit = e.target.closest(".abrir-modal-edit");

    if (modalEdit){
        const idInstitucion = modalEdit.dataset.id;

        consultarInformacionInstitucion(idInstitucion);
    }
});

document.getElementById("cerrar-modal-servicios").addEventListener("click", ()=>{
    document.getElementById("contenido-modal-servicios").innerHTML = "";
});

cerrarModal.addEventListener("click", ()=>{
    document.getElementById("nombre-institucion").value = "";
    document.getElementById("clave").value = "";
    document.getElementById("tag").value = "";
    document.getElementById("gestor-laboratorio").checked = false;
});

cerrarModalEdit.addEventListener("click", ()=>{
    document.getElementById("nombre-institucion-edit").value = "";
    document.getElementById("clave-edit").value = "";
    document.getElementById("tag-edit").value = "";
    document.getElementById("gestor-laboratorio-edit").checked = false;
    document.getElementById("id-institucion-edit").value = "";
})

async function consultarServicios(idInstitucion){
    const response = await fetch(`/admin-labores/instituciones/servicios?id=${idInstitucion}`);
    const data = await response.json();

    llenarModal(data);
}

async function consultarInformacionInstitucion(idInstitucion){
    const response = await fetch(`/admin-labores/instituciones/editar?id=${idInstitucion}`);
    const data = await response.json();

    llenarInformacionEditar(data);
}

function llenarModal(data){
    document.getElementById("contenido-modal-servicios").innerHTML += `<li class="${(data.gestor_laboratorio == '1') ? 'text-green-600' : 'text-red-600' }">Gestor de Laboratorios</li>`
}

function llenarInformacionEditar(data){
    document.getElementById("formulario-editar").action = `/labores/instituciones/${data.id}`;
    document.getElementById("nombre-institucion-edit").value = data.nombre;
    document.getElementById("clave-edit").value = data.clave;
    document.getElementById("tag-edit").value = data.tag;
    document.getElementById("gestor-laboratorio-edit").checked = (data.gestor_laboratorio == '1') ? true : false;
    document.getElementById("id-institucion-edit").value = data.id;
}

document.getElementById("boton-actualizar").addEventListener('click', (e)=>{
    e.preventDefault();

    let contrasena = prompt('Ingresa la contraseña para realizar esta accion:');
    
    if (contrasena !== null) {
        document.getElementById('contrasena_seguridad').value = contrasena;
        
        editar(document.getElementById("formulario-editar").action);
    }
});

async function editar(url){
    fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            'nombre' : document.getElementById("nombre-institucion-edit").value,
            'clave' : document.getElementById("clave-edit").value,
            'tag' : document.getElementById("tag-edit").value,
            'gestor_laboratorio' : (document.getElementById("gestor-laboratorio-edit").checked == true) ? '1' : '0',
            'contrasena_seguridad' : document.getElementById("contrasena_seguridad").value
        })
    })
    .then(response => response.json())
    .then(data => {
        // console.log(data);
        location.reload();
    });
}

