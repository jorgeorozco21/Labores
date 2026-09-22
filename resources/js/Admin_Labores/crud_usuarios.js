"use strict";

const cerrarModal = document.getElementById("cerrar-modal");
const cerrarModalEdit = document.getElementById("cerrar-modal-edit");

cerrarModal.addEventListener("click", ()=>{
    document.getElementById("nombre-usuario").value = "";
    document.getElementById("email").value = "";
    document.getElementById("nombre-completo").value = "";
    document.getElementById("institucion").selectedIndex = 0;
});

cerrarModalEdit.addEventListener("click", ()=>{
    document.getElementById("formulario-editar").action = "";
    document.getElementById("nombre-usuario-edit").value = "";
    document.getElementById("email-edit").value = "";
    document.getElementById("nombre-completo-edit").value = "";
    document.getElementById("institucion-edit").innerHTML = "";
})

document.addEventListener("click", (e)=>{
    const cambio = e.target.closest(".btn-cambiar-contrasena");

    if (cambio){
        const contrasena = prompt("Ingresa la contraseña para realizar esta accion:");

        if (contrasena === null) return;

        const idUsuario = cambio.dataset.id;

        cambioContrasena(idUsuario, contrasena);
    }

    const editar = e.target.closest(".abrir-modal-edit");

    if (editar){
        const id = editar.dataset.id;

        informacionEditar(id);
    }
});

async function cambioContrasena(idUsuario, contrasena){
    try {

        const respuesta = await fetch(`/labores/usuarios/${idUsuario}/cambiar-contrasena`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                'cambio_contrasena': contrasena
            })
        });

        const data = await respuesta.json();
        alert(data.message);

    } catch(error) {
        alert("Error al cambiar contraseña " + error);
    }
}

async function informacionEditar(id){
    const response = await fetch(`/admin-labores/usuarios/editar?id=${id}`);
    const data = await response.json();

    llenarInformacionEditar(data);
}

function llenarInformacionEditar(data){
    document.getElementById("formulario-editar").action = `/labores/usuarios/${data.usuarios[0].id}`;
    document.getElementById("nombre-usuario-edit").value = data.usuarios[0].nombre_usuario;
    document.getElementById("email-edit").value = data.usuarios[0].email;
    document.getElementById("nombre-completo-edit").value = data.usuarios[0].nombre;

    const selectInstitucion = document.getElementById("institucion-edit");
    let opcionesHTML = "";

    data.instituciones.forEach(i => {
        opcionesHTML += `<option value="${i.id}" ${(i.id == data.usuarios[0].id_institucion) ? 'selected':''}>${i.nombre}</option>`;
    });

    selectInstitucion.innerHTML = opcionesHTML;
}

document.getElementById("boton-actualizar").addEventListener('click', (e)=>{
    e.preventDefault();

    let contrasena = prompt('Ingresa la contraseña para realizar esta accion:');
    
    if (contrasena !== null) {
        document.getElementById('contrasena-seguridad-edit').value = contrasena;
        
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
            'nombre_usuario' : document.getElementById("nombre-usuario-edit").value,
            'email' : document.getElementById("email-edit").value,
            'nombre' : document.getElementById("nombre-completo-edit").value,
            'id_institucion' : document.getElementById("institucion-edit").value,
            'contrasena_seguridad' : document.getElementById('contrasena-seguridad-edit').value
        })
    })
    .then(response => response.json())
    .then(data => {
        // console.log(data);
        location.reload();
    });
}

