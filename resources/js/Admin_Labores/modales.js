"use strict";

const contendorModal = document.getElementById("modal");
const abrirModal = document.getElementById("abrir-modal");
const cerrarModal = document.getElementById("cerrar-modal");
const contenedorModalEdit = document.getElementById("modal-edit");
const cerrarModalEdit = document.getElementById("cerrar-modal-edit");

abrirModal.addEventListener("click",()=>{
    contendorModal.style.display = "flex";
});

cerrarModal.addEventListener("click",()=>{
    contendorModal.style.display = "none";
});

document.addEventListener("click", (e)=>{
    const modalEdit = e.target.closest(".abrir-modal-edit");

    if (modalEdit){
        contenedorModalEdit.style.display = "flex";
    }
});

cerrarModalEdit.addEventListener("click", ()=>{
    contenedorModalEdit.style.display = "none";
})