const s=document.getElementById("contenedor-reportes"),n=document.getElementById("contenedor-auditorias");let d=!1,i,c=!1,l;document.addEventListener("click",function(e){const o=e.target.closest(".reportes");if(o){const r=o.dataset.id;d=!0,i=r,f(r),document.getElementById("numero-computadora").innerHTML=`#${o.dataset.computadora}`,document.getElementById("reportes-modal").classList.remove("hidden"),document.body.classList.add("overflow-hidden")}const t=e.target.closest(".reporte");if(t){const r=t.dataset.idsolicitud;c=!0,l=r,x(r),document.getElementById("id-auditoria").innerHTML=`#${r}`,document.getElementById("auditoria-modal").classList.remove("hidden"),document.body.classList.add("overflow-hidden")}const a=e.target.closest(".cambiar-estado");if(a&&confirm("Deseas cambiar el estado del equipo ??")){const r=a.dataset.id;E(r)}const u=e.target.closest(".reemplazar");if(u&&confirm("El equipo ha sido reemplazado ??")){const r=u.dataset.id;k(r)}e.target.closest(".cerrar-modal-reportes")&&(s.innerHTML="",d=!1,i=null),e.target.closest(".cerrar-modal-auditoria")&&(n.innerHTML="",c=!1,l=null)});async function f(e){const t=await(await fetch(`/admin/informes/laboratorios/laboratorio-computo/reportes?id=${e}`)).json();v(t)}function v(e){if(s.innerHTML="",e.length===0)s.innerHTML=`
            <div class="mb-4 p-4 bg-[#F7F6F8] rounded-2xl relative group transition-all cursor-default">
                <p class="text-[11px] text-gray-700 font-bold leading-relaxed line-clamp-3">
                    No hay reportes.
                </p>
            </div>
        `;else{let o="";e.forEach(t=>{o+=`
                <div data-idsolicitud="${t.id}" class="reporte mb-4 p-4 bg-[#F7F6F8] rounded-2xl border-2 border-red-200 relative group hover:shadow-md hover:border-red-600 transition-all cursor-default">
                    <p class="text-[11px] text-gray-700 font-bold leading-relaxed line-clamp-3 uppercase">
                        ${t.tipo}
                    </p>
                    <p class="text-[11px] text-gray-500 font-bold leading-relaxed line-clamp-3">
                        ${t.descripcion}
                    </p>
                </div>
            `}),s.innerHTML=o}}async function x(e){const t=await(await fetch(`/admin/informes/laboratorios/laboratorio-computo/auditorias?id=${e}`)).json();w(t)}function w(e){if(e.length===0)n.innerHTML=`
            <div class="mb-4 p-4 bg-[#F7F6F8] rounded-2xl relative group transition-all cursor-default">
                <p class="text-[11px] text-gray-700 font-bold leading-relaxed line-clamp-3">
                    No hay auditorias.
                </p>
            </div>
        `;else{let o=`
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Usuario</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Estado</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
        `;e.forEach(t=>{const a=JSON.parse(t.info_usuario);o+=`
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div>
                                <p class="text-sm font-bold text-gray-800">${a.nombre}</p>
                                <p class="text-xs text-gray-400">${a.email}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 text-[10px] text-center font-bold rounded-lg bg-green-50 text-green-600 border border-green-100 uppercase">
                            ${t.estado}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">${t.fecha}</td>
                </tr>
            `}),o+=`
                </tbody>
            </table>
        `,n.innerHTML=o}}async function E(e){const o={id_computadora:e};try{const t=await fetch(`/admin/informes/laboratorios/laboratorio-computo/editar-computadora-${e}`,{method:"PUT",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content"),Accept:"application/json"},body:JSON.stringify(o)}),a=await t.json();t.ok?alert("Informacion actualizada correctamente"):alert("Error: "+a.message)}catch(t){console.error("Error al editar:",t)}}async function k(e){try{const o=await fetch(`/admin/informes/laboratorios/laboratorio-computo/reemplazar-computadora-${e}`,{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content"),Accept:"application/json"}}),t=await o.json();o.ok?alert("Informacion actualizada correctamente"):alert("Error: "+t.message)}catch(o){console.error("Error al editar:",o)}}document.getElementById("nueva-computadora").addEventListener("click",()=>{confirm("Deseas agregar una nueva computadora ??")&&T()});async function T(){try{const e=await fetch(`/admin/informes/laboratorios/laboratorio-computo/crear-computadora-${document.getElementById("id-lab").value}`,{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content"),Accept:"application/json"}}),o=await e.json();e.ok?alert("Computadora creada correctamente"):alert("Error: "+o.message)}catch(e){console.error("Error al editar:",e)}}const m=document.getElementById("informacion-filtrada"),b=document.getElementById("buscador"),h=document.getElementById("filtrar-tipo");async function p(){const o=await(await fetch(`/api/admin/informes/laboratorios/laboratorio-computo/buscador?idLab=${document.getElementById("id-lab").value}&texto=${b.value}&filtro=${h.value}`)).json();M(o)}let g;const $=300;b.addEventListener("input",()=>{clearTimeout(g),g=setTimeout(()=>{p()},$)});setInterval(()=>{p()},5e3);h.addEventListener("change",()=>{p()});function M(e){m.innerHTML="";let o="";e.forEach(t=>{o+=`
            <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-4 text-sm text-black font-medium text-center">${t.numero_computadora}</td>
                <td class="px-6 py-4 text-center">
                <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg ${t.estado=="activo"?"bg-green-50 text-green-600 border border-green-100":"bg-red-50 text-red-600 border border-red-100"} w-fit">
                        ${t.estado}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center">
                        <button data-id="${t.id}" data-computadora="${t.numero_computadora}"
                            class="reportes flex items-center gap-2 text-[#7B1FA3] hover:text-white">
                            <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center">
                        <button data-id="${t.id}" class="cambiar-estado flex items-center gap-2 p-2 rounded-lg text-sm text-black hover:text-[#7B1FA3] hover:bg-purple-50 transition-colors group">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7h-9M20 7l-3-3M20 7l-3 3M4 17h9M4 17l3 3M4 17l3-3" />
                            </svg>
                            <span class="font-medium">Cambiar estado</span>
                        </button>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center">
                        <button data-id="${t.id}" class="reemplazar flex items-center gap-2 p-2 rounded-lg text-sm text-black hover:text-[#7B1FA3] hover:bg-purple-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M23 4v6h-6M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
                            </svg>
                            <span class="font-medium">Reemplazar equipo</span>
                        </button>
                    </div>
                </td>
            </tr>
        `}),m.innerHTML=o}setInterval(()=>{d&&f(i)},5e3);setInterval(()=>{c&&x(l)},5e3);
