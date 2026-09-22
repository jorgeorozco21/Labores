import{b as m}from"./buscador_materiales-CuP23CSm.js";const n=document.getElementById("contenedor-materiales-solicitar"),b=document.getElementById("enviar"),d=document.getElementById("buscador"),l=document.getElementById("id-laboratorio").value,e={},g={id:document.getElementById("id_usuario").value,nombre:document.getElementById("nombre_usuario").value,email:document.getElementById("email").value,grado:document.getElementById("grado").value,grupo:document.getElementById("grupo").value,nombreGrupo:document.getElementById("nombreGrupo").value,turno:document.getElementById("turno").value,idLaboratorio:l,nombreLaboratorio:document.getElementById("nombreLaboratorio").value},p=parseInt(document.getElementById("limite-solicitudes").value);let s=parseInt(document.getElementById("cantidad-solicitudes").value);document.addEventListener("click",function(a){if(a.target.closest(".tarjeta-material")){if(a.target.dataset.id==null)return;let t=a.target.dataset.id,o=a.target.dataset.nombre,c=a.target.dataset.tipo,u=a.target.dataset.cantidaddisponible;t in e||(e[t]={id:t,nombre:o,tipo:c,cantidad:1,cantidad_maxima:u}),i(e),window.innerWidth<1024&&setTimeout(()=>{openCart()},150)}const r=a.target.closest(".eliminar-material");if(r){const t=r.dataset.ideliminar;console.log("ID a eliminar:",t),delete e[t],i(e)}if(a.target.closest(".operacion")){let t=a.target.dataset.idsum;a.target.dataset.op=="sum"?e[t].cantidad<e[t].cantidad_maxima&&e[t].cantidad++:e[t].cantidad>1&&e[t].cantidad--,i(e)}});function i(a){n.innerHTML="";let r="";for(let t in a)e[t].tipo=="prestamos por unidad"?r+=`
                <div class="p-4 bg-[#F7F6F8] rounded-2xl border border-gray-100 relative group hover:shadow-md hover:border-gray-200 transition-all cursor-default">
                    <button data-ideliminar="${e[t].id}" class="eliminar-material absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <p class="text-sm font-bold text-gray-800 pr-6 mb-3">${e[t].nombre}</p>
                    <div class="inline-flex items-center justify-center w-10 h-10 bg-white border border-gray-200 rounded-xl shadow-sm">
                        <span class="text-sm font-bold text-gray-700">${e[t].cantidad}</span>
                    </div>
                </div>
            `:r+=`
                <div class="p-4 bg-[#F7F6F8] rounded-2xl border border-gray-100 relative group hover:shadow-md hover:border-gray-200 transition-all cursor-default">
                    <button data-ideliminar="${e[t].id}" class="eliminar-material absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <p class="text-sm font-bold text-gray-800 pr-6 mb-3">${e[t].nombre}</p>
                    <div class="flex items-center bg-white border border-gray-200 rounded-xl w-fit shadow-sm overflow-hidden">
                        <button data-idsum="${e[t].id}" data-op="res" class="operacion px-3 py-1.5 text-gray-400 hover:bg-gray-50 border-r transition-colors">-</button>
                        <span class="px-5 py-1.5 text-sm font-bold text-gray-700">${e[t].cantidad}</span>
                        <button data-idsum="${e[t].id}" data-op="sum" class="operacion px-3 py-1.5 text-gray-400 hover:bg-gray-50 border-l transition-colors">+</button>
                    </div>
                </div>
            `;n.innerHTML=r}b.addEventListener("click",a=>{if(a.preventDefault(),Object.keys(e).length==0)alert("No puedes realizar una solicitud vacia");else{if(s+1>p){alert("Alcanzaste el limite de solicitudes permitidas por usuario en este laboratorio.");return}if(confirm("Deseas hacer la solicitud ??")){const t=Object.values(e).map(o=>({id:o.id,nombre:o.nombre,cantidad:o.cantidad}));v(t),n.innerHTML="";for(let o in e)delete e[o];d.value="",m(d.value,l)}}});async function v(a){const r={info_usuario:g,info_material:a,fecha:new Date().toISOString().slice(0,19).replace("T"," ")};try{const t=await fetch("/usuario/normal/crear-solicitud",{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")},body:JSON.stringify(r)}),o=await t.json();t.ok?(alert("¡Solicitud guardada con éxito!"),s=parseInt(o),document.getElementById("cantidad-solicitudes").value=s):alert(o.error)}catch(t){console.error("Error de conexión:",t)}}async function f(){(await(await fetch(`/usuario/normal/materiales?texto=${d.value}&idLab=${l}`)).json()).forEach(t=>{t.id in e&&(e[t.id]={id:t.id,nombre:t.nombre,tipo:t.tipo,cantidad:Math.min(e[t.id].cantidad,t.cantidad_disponible),cantidad_maxima:t.cantidad_disponible})}),i(e)}setInterval(()=>{f()},5e3);
