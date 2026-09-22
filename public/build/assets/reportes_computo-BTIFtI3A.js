const i={id:document.getElementById("id_usuario").value,nombre:document.getElementById("nombre").value,email:document.getElementById("email").value},s=document.getElementById("contenedor-reportes");document.addEventListener("click",o=>{const e=o.target.closest(".cambiar");if(e&&confirm("Deseas cambiar el estado del reporte ??")){const a=e.dataset.id,r=e.dataset.estado;c(a,r),n()}const t=o.target.closest(".reportar");if(t&&confirm("La computadora no funciona ??")){const a=t.dataset.id,r=t.dataset.idsolicitud;l(a,r),n()}});async function c(o,e){const t={id_solicitud:o,estado:e,info_usuario:i};try{const a=await fetch("/usuario/mantenimiento/actualizar-solicitudes-computo",{method:"POST",headers:{"Content-Type":"application/json",Accept:"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")},body:JSON.stringify(t)}),r=await a.json();a.ok?alert("Reporte actualizado correctamente"):alert(r.error)}catch(a){console.error("Error de conexión:",a)}}async function n(){const e=await(await fetch("/usuario/mantenimiento/actualizar-informacion-reportes")).json();d(e)}function d(o){s.innerHTML="";let e="";o.forEach(t=>{const r=new Date(t.fecha).toLocaleDateString("es-ES",{day:"2-digit",month:"2-digit",year:"numeric"});e+=`
            <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-4 text-sm text-black text-center font-medium">
                    ${t.numero_computadora}
                </td>

                <!-- Material Dañado -->
                <td class="px-6 py-4 text-center text-black text-sm font-medium tracking-tight">
                        ${t.nombre}
                </td>

                <td class="px-6 py-4 text-sm text-black uppercase text-center font-medium">
                    ${t.tipo}
                </td>

                <!-- Descripcion -->
                <td class="px-6 py-4 justify-center">
                    <div class="flex justify-center">
                        <button type="button" onclick="openMaterialModal('${t.id}', '${t.descripcion}')" 
                            class="flex items-center gap-2 text-[#7B1FA3] hover:text-white" title="Ver Reporte">
                            <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </td>

                <!-- Fecha -->
                <td class="px-6 py-4 text-sm text-gray-500 text-center">
                    ${r}
                </td>

                <!-- Estado del Reporte -->
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center">
                        <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-bold rounded-lg border border-green-100 uppercase">
                            ${t.estado}
                        </span>
                    </div>
                </td>

                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center">
                        <span class="px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-bold rounded-lg border border-orange-100 uppercase">
                            ${t.estado=="aceptada"||t.estado=="reprogramado"?"en proceso":"reparado"}
                        </span>

                        <button data-estado="${t.estado=="aceptada"||t.estado=="reprogramado"?"en proceso":"reparado"}" data-id="${t.id}"
                            class="cambiar px-3 py-1 bg-[#7B1FA3] text-white rounded-xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98] ml-2"
                            title="Guardar cambio">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
        `,t.estado!="aceptada"&&t.estado!="reprogramado"&&(e+=`
                    <div class="flex justify-center">
                        <button data-idsolicitud="${t.id}" data-id="${t.id_computadora}" class="reportar flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all text-xs font-bold">
                            Reportar
                        </button>
                    </div>
            `),e+=`
                </td>
            </tr>
        `}),s.innerHTML=e}async function l(o,e){const t={estado:"sin reparacion",info_usuario:i,id_solicitud:e};try{const a=await fetch(`/usuario/matenimiento/editar-computadora-${o}`,{method:"PUT",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content"),Accept:"application/json"},body:JSON.stringify(t)}),r=await a.json();a.ok?alert("Informacion actualizada correctamente"):alert("Error: "+r.message)}catch(a){console.error("Error al editar:",a)}}setInterval(()=>{n()},5e3);
