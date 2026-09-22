const i=document.getElementById("contenedor-tarjetas"),a=document.getElementById("buscador"),r=document.getElementById("filtrar-tipo");async function s(){const e=await(await fetch(`/admin/informes/laboratorios/buscador?texto=${a.value}&tipo=${r.value}`)).json();i.innerHTML=l(e)}let n;const d=300;a.addEventListener("input",()=>{clearTimeout(n),n=setTimeout(()=>{s()},d)});function l(t){let e="";return console.log(t),t.forEach(o=>{e+=`
            <a href="/admin/informes/laboratorios/${o.id}-${o.tipo=="prestamos"?"laboratorio-normal":"laboratorio-computo/computadoras"}" class="flex flex-col gap-2 cursor-pointer">
                <div class="bg-white p-5 rounded-[20px] border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-all h-full">
                    <!-- Tipo de laboratorio -->
                    <div class="mb-4">
                        <span class="bg-[#E0E7FF] text-[#3730A3] text-[10px] font-bold px-3 py-1 rounded-full tracking-wider uppercase">
                            ${o.tipo}
                        </span>
                    </div>

                    <!-- Nombre -->
                    <h3 class="font-bold text-gray-900 text-base leading-tight">
                        ${o.nombre}
                    </h3>
                </div>
            </a>
        `}),e}r.addEventListener("change",()=>{s()});
