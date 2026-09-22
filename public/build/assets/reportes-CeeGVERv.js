const r=document.getElementById("contenedor-auditorias");let s,n=!1;document.addEventListener("click",a=>{const e=a.target.closest(".auditoria");if(e){const o=e.dataset.id;c(o),s=o,n=!0,document.getElementById("id-auditoria").innerHTML=`#${o}`,document.getElementById("auditoria-modal").classList.remove("hidden"),document.body.classList.add("overflow-hidden")}a.target.closest(".cerrar-modal-auditoria")&&(r.innerHTML="",n=!1,s=null)});async function c(a){const t=await(await fetch(`/admin/informes-reportes/laboratorios/laboratorio-normal/auditorias?id=${a}`)).json();x(t)}function x(a){if(a.length===0)r.innerHTML=`
            <div class="mb-4 p-4 bg-[#F7F6F8] rounded-2xl relative group transition-all cursor-default">
                <p class="text-[11px] text-gray-700 font-bold leading-relaxed line-clamp-3">
                    No hay auditorias.
                </p>
            </div>
        `;else{let e=`
            <table class="w-full text-left border-collapse min-w-[7 text-center00px]">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Usuario</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Estado</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
        `;a.forEach(t=>{const o=JSON.parse(t.info_usuario);e+=`
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div>
                                <p class="text-sm font-bold text-gray-800">${o.nombre}</p>
                                <p class="text-xs text-gray-400">${o.email}</p>
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
            `}),e+=`
                </tbody>
            </table>
        `,r.innerHTML=e}}setInterval(()=>{n&&c(s)},5e3);const d=document.getElementById("informacion-filtrada"),p=document.getElementById("buscador"),u=document.getElementById("filtro-tipo");async function i(){const e=await(await fetch(`/api/admin/informes-reportes/laboratorios/laboratorio-normal/buscador?idLab=${document.getElementById("id-lab").value}&texto=${p.value}&filtro=${u.value}`)).json();g(e)}let l;const m=300;p.addEventListener("input",()=>{clearTimeout(l),l=setTimeout(()=>{i()},m)});u.addEventListener("change",()=>{i()});setInterval(()=>{i()},5e3);function g(a){d.innerHTML="";let e="";a.forEach(t=>{const o=JSON.parse(t.info_usuario);e+=`
            <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-4 text-sm text-black font-medium text-center">
                    ${t.id}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-800 truncate">${o.nombre}</p>
                            <p class="text-[10px] text-gray-400 font-medium">${o.email}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-black font-medium text-center">
                    ${t.nombre}
                </td>
                <td class="px-6 py-4 text-sm text-black font-medium text-center">
                    ${t.cantidad}
                </td>
                <td class="px-6 py-4 justify-center">
                    <div class="flex justify-center">
                        <button type="button" 
                            onclick="openMaterialModal('${t.id}', '${t.descripcion}')" 
                            class="flex items-center gap-2 text-[#7B1FA3] hover:text-white transition-colors"
                            title="Ver Descripcion">
                            <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center">
                        <button data-id="${t.id}"
                            class="auditoria flex items-center gap-2 text-[#7B1FA3] hover:text-white transition-colors">
                            <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 text-center">
                    ${t.fecha}
                </td>
            </tr>
        `}),d.innerHTML=e}
