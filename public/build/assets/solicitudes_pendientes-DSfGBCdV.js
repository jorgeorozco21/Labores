const l=document.getElementById("contenedor-solicitudes"),c=document.getElementById("buscador"),d=document.getElementById("filtro"),m={id:document.getElementById("id_usuario").value,nombre:document.getElementById("nombre").value,email:document.getElementById("email").value};l.addEventListener("click",function(o){const a=o.target.closest(".aceptada"),t=o.target.closest(".rechazada");if(a){const e=a.dataset.id;confirm("¿Deseas aceptar la solicitud?")&&(h(e),c.value="",d.selectedIndex=0,n())}if(t){const e=t.dataset.id,i=t.dataset.idlaboratorio,r=t.dataset.idusuario,s=t.dataset.fecha;confirm("¿Deseas rechazar la solicitud?")&&(g(e,i,r,s),c.value="",d.selectedIndex=0,n())}});async function g(o,a,t,e){const i={id_solicitud:o,id_usuario:t,id_laboratorio:a,fecha:e};try{const r=await fetch(`/usuario/encargado/rechazar-solicitud-prestamos/${o}`,{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content"),Accept:"application/json"},body:JSON.stringify(i)}),s=await r.json();r.ok?alert("Solicitud rechazada."):alert("Error: "+s.message)}catch(r){console.error("Error en la conexión:",r)}}async function h(o){const a={id_solicitud:o,info_usuario:m,estado:"aceptada"};try{const t=await fetch("/usuario/encargado/actualizar-solicitudes",{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")},body:JSON.stringify(a)}),e=await t.json();t.ok?alert("Solicitud aceptada correctamente"):alert(e.error)}catch(t){console.error("Error de conexión:",t)}}function f(o){l.innerHTML="";let a="";o.forEach(t=>{const e=JSON.parse(t.info_usuario),i=JSON.parse(t.info_material),r=JSON.stringify(i).replace(/"/g,"&quot;"),p=new Date(t.fecha).toLocaleDateString("es-ES",{day:"2-digit",month:"2-digit",year:"numeric"});a+=`
            <tr class="hover:bg-gray-50/50 transition-colors group">
                <!-- Nombre, Correo y Grado/Grupo -->
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-800 truncate">${e.nombre}</p>
                            <p class="text-[10px] text-gray-400 font-medium">${e.email}</p>
                            <p class="text-[10px] text-gray-400 font-medium">${e.grado}° ${e.grupo} - ${e.nombreGrupo} - ${e.turno}</p>
                        </div>
                    </div>
                </td>

                <!-- ID de Solicitud -->
                <td class="px-6 py-4 text-sm text-black text-center font-medium">
                    ${t.id}
                </td>

                <!-- Laboratorio -->
                <td class="px-6 py-4 text-center text-black text-sm font-medium tracking-tight">
                    ${e.nombreLaboratorio}
                </td>

                <!-- Lista de Materiales -->
                <td class="px-6 py-4 justify-center">
                    <div class="flex justify-center">
                        <button type="button" onclick="openMaterialModal(${t.id}, ${r})" 
                            class="flex items-center gap-2 text-[#7B1FA3] hover:text-white transition-colors">
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
                    ${p}
                </td>

                <!-- Acciones -->
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-3">
                        <!-- Aprobar Solicitud -->
                        <button data-id="${t.id}" class="aceptada flex items-center gap-1 px-3 py-1.5 rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all text-xs font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Aprobar
                        </button>

                        <!-- Rechazar Solicitud -->
                        <button data-id="${t.id}" data-idlaboratorio="${e.idLaboratorio}" data-idusuario="${e.id}" data-fecha="${t.fecha}" class="rechazada flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all text-xs font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Rechazar
                        </button>
                    </div>
                </td>
            </tr>
        `}),l.innerHTML=a}async function n(){const a=await(await fetch(`/api/usuario/encargado/solicitudes-pendientes?texto=${c.value}&filtro=${d.value}`)).json();f(a)}let u;const x=300;c.addEventListener("input",()=>{clearTimeout(u),u=setTimeout(()=>{n()},x)});d.addEventListener("change",()=>{n()});setInterval(()=>{n()},5e3);
