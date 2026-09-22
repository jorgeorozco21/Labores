const r=document.getElementById("contenedor-materiales-solicitar"),d=document.getElementById("contenedor-solicitudes"),i=document.getElementById("eliminar-solicitud"),v=document.getElementById("idLaboratorio").value;let c,u=!1;document.addEventListener("click",a=>{const e=a.target.closest(".solicitud");if(e){const s=e.dataset.id,o=e.dataset.estado;c=s,u=!0,!o||o==="null"||o===""?(i.dataset.ideliminar=s,i.classList.remove("bg-gray-400"),i.classList.add("bg-purple-700","hover:bg-[#7B1FA3]"),i.disabled=!1):(delete i.dataset.ideliminar,i.classList.remove("bg-purple-700","hover:bg-[#7B1FA3]"),i.classList.add("bg-gray-400"),i.disabled=!0),m(),p(s)}const t=a.target.closest(".solicitud-eliminada");if(t){const s=t.dataset.id;x(s),l()}});async function p(a){const t=await(await fetch(`/usuario/normal/laboratorio/informacion-solicitud?id=${a}`)).json();g(t.info_material)}function g(a){r.innerHTML="";let e="";a=JSON.parse(a),a.forEach(t=>{e+=`
            <div class="p-4 bg-[#F7F6F8] rounded-2xl border border-gray-100 relative group hover:shadow-md hover:border-gray-200 transition-all cursor-default">
                <p class="text-sm font-bold text-gray-800 pr-6 mb-3">${t.nombre}</p>
                <div class="inline-flex items-center justify-center w-10 h-10 bg-white border border-gray-200 rounded-xl shadow-sm">
                    <span class="text-sm font-bold text-gray-700">${t.cantidad}</span>
                </div>
            </div>
        `}),r.innerHTML=e}const f=document.getElementById("cart"),n=document.getElementById("overlay");function m(){f.classList.remove("translate-y-full"),n.classList.remove("opacity-0","pointer-events-none")}function h(){r.innerHTML="",f.classList.add("translate-y-full"),n.classList.add("opacity-0","pointer-events-none")}n.addEventListener("click",h);i.addEventListener("click",()=>{if(confirm("Deseas cancelar la solicitud ??")){const e=i.dataset.ideliminar;r.innerHTML="",delete i.dataset.ideliminar,w(e),l()}});async function w(a){try{const e=await fetch(`/usuario/normal/eliminar-solicitud/${a}`,{method:"DELETE",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content"),Accept:"application/json"}}),t=await e.json();e.ok?alert("Solicitud cancelada."):alert("Error: "+t.message)}catch(e){console.error("Error en la conexión:",e)}}async function x(a){try{const e=await fetch(`/usuario/normal/eliminar-solicitud-eliminada/${a}`,{method:"DELETE",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content"),Accept:"application/json"}})}catch(e){console.error("Error en la conexión:",e)}}async function l(){const e=await(await fetch(`/usuario/normal/actualizar-solicitudes?id=${v}`)).json();b(e)}function b(a){d.innerHTML="";let e="";a.solicitudes_eliminadas.forEach(t=>{e+=`
            <div data-id="${t.id}" class="solicitud-eliminada bg-white p-6 rounded-[20px] border border-gray-100 shadow-sm flex w-full hover:shadow-md hover:border-2 transition-shadow cursor-pointer">
                <div class="space-y-2 w-full">
                    <div class="flex justify-between w-full">
                        <h2 class="text-lg font-extrabold text-[#1e293b] tracking-tight">
                            Solicitud de Materiales
                        </h2>
                    </div>
                    <div class="space-y-1 flex justify-between items-center">
                        <div>
                            <!-- ID de la Solicitud -->
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                <span class="text-gray-600">ID:</span> ${t.id_solicitud}
                            </p>
                            <!-- Fecha de la Solicitud -->
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                <span class="text-gray-600">Fecha:</span> ${t.fecha}
                            </p>
                        </div>
                        <div>
                            <div class="w-12 h-12 flex items-center justify-center bg-red-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `}),a.solicitudes.forEach(t=>{e+=`
            <div data-id="${t.id}" data-estado="${t.estado}" class="solicitud bg-white p-6 rounded-[20px] border border-gray-100 shadow-sm flex w-full hover:shadow-md hover:border-[#7B1FA3] hover:border-2 transition-shadow cursor-pointer">
                <div class="space-y-2 w-full">
                    <div class="flex justify-between w-full">
                        <h2 class="text-lg font-extrabold text-[#1e293b] tracking-tight">
                            Solicitud de Materiales
                        </h2>
                    </div>
                    <div class="space-y-1 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                <span class="text-gray-600">ID:</span> ${t.id}
                            </p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                <span class="text-gray-600">Fecha:</span> ${t.fecha}
                            </p>
                        </div>
                        <div>
        `,t.estado==null?e+=`
                            <div class="w-12 h-12 flex items-center justify-center bg-amber-50 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
            `:t.estado=="aceptada"?e+=`
                            <div class="w-12 h-12 flex items-center justify-center bg-green-50 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
            `:t.estado=="en prestamo"&&(e+=`
                            <div class="w-12 h-12 flex items-center justify-center bg-green-50 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
            `),e+=`
                        </div>
                    </div>
                </div>
            </div>
        `}),d.innerHTML=e}setInterval(()=>{l()},5e3);setInterval(()=>{u&&p(c)},5e3);
