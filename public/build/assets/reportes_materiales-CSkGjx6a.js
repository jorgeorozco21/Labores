const c={id:document.getElementById("id_usuario").value,nombre:document.getElementById("nombre").value,email:document.getElementById("email").value},d=document.getElementById("contenedor-reportes");document.addEventListener("click",o=>{const t=o.target.closest(".cambiar");if(t&&confirm("Deseas cambiar el estado del reporte ??")){const a=t.dataset.id,n=t.dataset.estado;l(a,n),i()}const e=o.target.closest(".reportar");if(e&&confirm("El material no tiene reparacion ??")){const a=e.dataset.id,n=e.dataset.estado,r=e.dataset.inventario,s=e.dataset.cantidad;u(a,n,r,s),i()}});async function l(o,t){const e={id_reporte:o,estado:t,info_usuario:c};try{const a=await fetch("/usuario/mantenimiento/actualizar-reportes-materiales",{method:"POST",headers:{"Content-Type":"application/json",Accept:"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")},body:JSON.stringify(e)}),n=await a.json();a.ok?alert("Reporte actualizado correctamente"):alert(n.error)}catch(a){console.error("Error de conexión:",a)}}async function i(){const t=await(await fetch("/usuario/mantenimiento/actualizar-informacion-reportes-mateiales")).json();p(t)}function p(o){d.innerHTML="";let t="";o.forEach(e=>{const n=new Date(e.fecha).toLocaleDateString("es-ES",{day:"2-digit",month:"2-digit",year:"numeric"});t+=`
            <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-4 text-sm text-black text-center font-medium">
                    ${e.nombre}
                </td>

                <td class="px-6 py-4 text-sm text-black text-center font-medium">
                    ${e.cantidad}
                </td>

                <td class="px-6 py-4 text-center text-black text-sm font-medium tracking-tight">
                        ${e.nombreLaboratorio}
                </td>

                <!-- Descripcion -->
                <td class="px-6 py-4 justify-center">
                    <div class="flex justify-center">
                        <button type="button" onclick="openMaterialModal('${e.id}', '${e.descripcion}')" 
                            class="flex items-center gap-2 text-[#7B1FA3] hover:text-white" title="Ver Reporte">
                            <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                        </button>
                    </div
                </td>

                <!-- Fecha -->
                <td class="px-6 py-4 text-sm text-gray-500 text-center">
                    ${n}
                </td>

                <!-- Estado del Reporte -->
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center">
                        <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-bold rounded-lg border border-green-100 uppercase">
                            ${e.estado==null?"espera":e.estado}
                        </span>
                    </div>
                </td>

                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center">
                        <span class="px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-bold rounded-lg border border-orange-100 uppercase">
                            ${e.estado==null||e.estado=="reprogramado"?"en proceso":"reparado"}
                        </span>

                        <button data-estado="${e.estado==null||e.estado=="reprogramado"?"en proceso":"reparado"}" data-id="${e.id}"
                            class="cambiar px-3 py-1 bg-[#7B1FA3] text-white rounded-xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98] ml-2"
                            title="Guardar cambio">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </td>

                <td class="px-6 py-4 text-center">
        `,e.estado=="en proceso"&&(t+=`
                <div class="flex justify-center">
                    <button data-id="${e.id}" data-estado="sin reparacion" data-inventario="${e.id_inventario}" data-cantidad="${e.cantidad}" class="reportar flex items-center gap-1 px-3 py-1 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all text-xs font-bold">
                        Sin Reparacion
                    </button>
                </div>
            `),t+=`
                </td>
            </tr>
        `}),d.innerHTML=t}async function u(o,t,e,a){const n={id_reporte:o,estado:t,info_usuario:c,id_inventario:e,cantidad:a};try{const r=await fetch("/usuario/mantenimiento/reporte-sin-funcionamiento",{method:"POST",headers:{"Content-Type":"application/json",Accept:"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")},body:JSON.stringify(n)}),s=await r.json();r.ok?(console.log(s),alert("Material eliminado")):alert(s.error)}catch(r){console.error("Error de conexión:",r)}}setInterval(()=>{i()},5e3);
