"use strict";

const tipoUsuario = document.getElementById("tipo-normal");
const cerraModal = document.getElementById("cerrar-modal");

// Reiniciar los datos del formulario de crear
cerraModal.addEventListener("click", ()=>{
    document.getElementById("nombre-usuario").value = "";
    document.getElementById("email").value = "";
    document.getElementById("nombre-completo").value = "";

    document.getElementById("tipo-normal").checked = false;
    document.getElementById("tipo-encargado").checked = false;
    document.getElementById("tipo-mantenimiento").checked = false;

    document.getElementById("grupo").selectedIndex = 0;

    document.getElementById("label-grupo").style.display = "none";
    document.getElementById("grupo").style.display = "none";
});

// Esta funcion solo es para saber en que momento mostrar el filtro de grupos
tipoUsuario.addEventListener("click", ()=>{
    if (!tipoUsuario.checked){
        document.getElementById("label-grupo").style.display = "none";
        document.getElementById("grupo").style.display = "none";
    }else{
        document.getElementById("label-grupo").style.display = "flex";
        document.getElementById("grupo").style.display = "flex";
    }
});

const tipoUsuarioEdit = document.getElementById("tipo-normal-edit");

// Esta funcion solo es para saber en que momento mostrar el filtro de grupos en formulario de editar
tipoUsuarioEdit.addEventListener("click", ()=>{
    if (!tipoUsuarioEdit.checked){
        document.getElementById("label-grupo-edit").style.display = "none";
        document.getElementById("grupo-edit").style.display = "none";
    }else{
        document.getElementById("label-grupo-edit").style.display = "flex";
        document.getElementById("grupo-edit").style.display = "flex";
    }
});

// Funcion para obtener la informacion en tiempo real para llenar el formulario de editar
async function informacionEditar(id){
    const response = await fetch(`/api/usuarios/editar?id=${id}`);
    const data = await response.json();

    // LLamo la funcion que asigna la infromacion al formulario
    asignarInformacionFormularioEditar(data);
}

// funcion para asignar infromacion al formualrio de editar
function asignarInformacionFormularioEditar(informacion){

    document.getElementById('formulario-editar').action = `/admin/usuarios/${informacion.usuario.id}`;
    document.getElementById('nombre-usuario-edit').value = informacion.usuario.nombre_usuario;
    document.getElementById('email-edit').value = informacion.usuario.email;
    document.getElementById('nombre-completo-edit').value = informacion.usuario.nombre;

    // Aqui si el Tipo de usuario es normal muestra el select para indicar el nuevo o el viejo grupo y si no es normal no lo muestra
    if (informacion.usuario.normal == "0"){
        document.getElementById("label-grupo-edit").style.display = "none";
        document.getElementById("grupo-edit").style.display = "none";
        document.getElementById("tipo-normal-edit").checked = false;
    }else{
        document.getElementById("grupo-edit").style.display = "flex";
        document.getElementById("tipo-normal-edit").checked = true;
        
        const gruposEditar = document.getElementById("grupo-edit");

        let opciones  = '';

        // Creacion de opciones para el formulario de editar el grupo
        for (const grupo of informacion.grupos){
            opciones += `
                <option value="${grupo.id}" ${ (informacion.usuario.id_grupo == grupo.id)?"selected":"" }>${grupo.grado} ${grupo.grupo} - ${grupo.nombre} - ${grupo.turno}</option>
            `;
        }

        gruposEditar.innerHTML = opciones;
    }

    if (informacion.usuario.encargado == "0") document.getElementById("tipo-encargado-edit").checked = false;
    else document.getElementById("tipo-encargado-edit").checked = true;

    if (informacion.usuario.mantenimiento == "0") document.getElementById("tipo-mantenimiento-edit").checked = false;
    else document.getElementById("tipo-mantenimiento-edit").checked = true;
}

const contendorModalEdit = document.getElementById("modal-edit");
const cerrarModalEdit = document.getElementById("cerrar-modal-edit"); 

// Funcion para cuando se abra el formulario de editar se mande una peticion a la base de datos
document.addEventListener("click", function(e){ 
    const btn = e.target.closest(".abrir-modal-edit");
    if(btn){ 

        contendorModalEdit.style.display = "flex"; 

        let id = btn.dataset.id;
        
        informacionEditar(id); 
    } 
});

// funcion para cerrar el formulario de editar
cerrarModalEdit.addEventListener("click",()=>{
    contendorModalEdit.style.display = "none";

    document.getElementById("nombre-usuario-edit").value = "";
    document.getElementById("email-edit").value = "";
    document.getElementById("nombre-completo-edit").value = "";

    document.getElementById("tipo-normal-edit").checked = false;
    document.getElementById("tipo-encargado-edit").checked = false;
    document.getElementById("tipo-mantenimiento-edit").checked = false;

    document.getElementById("grupo-edit").selectedIndex = 0;

    document.getElementById("label-grupo-edit").style.display = "none";
    document.getElementById("grupo-edit").style.display = "none";
});

// Esta funcion es la encargada de ejecutar la funcion para editar la contraseña
document.addEventListener("click", async function(e){
    const btn = e.target.closest(".btn-cambiar-contrasena");

    if(btn){

        if (!confirm("Seguro que deseas cambiar la contraseña?")) return;

        const url = btn.dataset.url;
        const token = document.querySelector('meta[name="csrf-token"]').content;

        try {

            const respuesta = await fetch(url, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": token,
                    "Content-Type": "application/json"
                }
            });

            const data = await respuesta.json();
            alert(data.message);

        } catch(error) {
            alert("Error al cambiar contraseña");
        }
    }
});

const modalEdicionMasiva = document.getElementById("modal-edicion-masiva");
const abrirModalEdicionMasiva = document.getElementById("edicion-masiva");
const cerrarModalEdicionMasiva = document.getElementById("cerrar-modal-edicion-masiva");
const botonEdicionMasiva = document.getElementById("boton-edicion-masiva");
const grupoActual = document.getElementById('grupo-actual');
const grupoNuevo = document.getElementById("grupo-nuevo");

abrirModalEdicionMasiva.addEventListener('click', function (){
    modalEdicionMasiva.style.display = "flex";
});

botonEdicionMasiva.addEventListener('click', function (){
    if (grupoActual.value == grupoNuevo.value){
        alert('No de puede editar si esta seleccionado el mismo grupo');
    }else{
        if (confirm('Deseas realizar los cambios ??')){

            editarRegistros(grupoActual.value, grupoNuevo.value);

            grupoActual.selectedIndex = 0;
            grupoNuevo.selectedIndex = 0;
            modalEdicionMasiva.style.display = "none";
        }
    }
});

function editarRegistros(grupoactual, gruponuevo){
    fetch(`/admin/usuarios/edicion-masiva`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ 
            grupoActual: grupoactual,
            grupoNuevo: gruponuevo
        })
    })
    .then(respuesta => respuesta.json())
    .then(datos => {
        if (datos.success) {

            location.reload(); 
        } else {
            alert("Error: " + datos.message);
        }
    })
    .catch(error => {
        console.error("Error en la petición:", error);
        alert("No se pudo conectar con el servidor.");
    });
}

cerrarModalEdicionMasiva.addEventListener('click', function (){
    grupoActual.selectedIndex = 0;
    grupoNuevo.selectedIndex = 0;
    modalEdicionMasiva.style.display = "none";
});

/*const filtroTipo = document.getElementById("filtrar-tipo");

// Funcion para desaparecer y desaparecer el filtro de grupo
filtroTipo.addEventListener("change", ()=>{
    if (filtroTipo.value != "normal" && filtroTipo.value != "Sin Filtro"){
        document.getElementById("filtrar-grupo-label").style.display = "none";
        document.getElementById("filtrar-grupo").style.display = "none";
        document.getElementById("filtrar-grupo").value = "Sin Filtro";
    }else{
        document.getElementById("filtrar-grupo-label").style.display = "inline-block";
        document.getElementById("filtrar-grupo").style.display = "inline-block";
        document.getElementById("filtrar-grupo").value = "Sin Filtro";
    }
});

const filtroGrupo = document.getElementById("filtrar-grupo");

// Funcion para cambiar el valor del filtro tipo usuario cuando filtro grupo tenga cierto valor
filtroGrupo.addEventListener("change", ()=>{
    if (filtroGrupo.value != "Sin Filtro"){
        filtroTipo.value = "normal";
    }
});*/