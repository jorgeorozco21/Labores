"use strict";

document.addEventListener("DOMContentLoaded", ()=>{
    const alerta1 = document.querySelector(".success");
    const alerta2 = document.querySelector(".errores");

    if (alerta1) {
        setTimeout(()=>{
            alerta1.style.display = "none";
        },3000);
    }

    if (alerta2) {
        setTimeout(()=>{
            alerta2.style.display = "none";
        },3000);
    }
});

