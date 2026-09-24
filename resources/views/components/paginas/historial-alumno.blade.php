@props(['historial'])

<div class="max-w-6xl mx-auto bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex flex-col pb-6 mb-8 border-b border-gray-100 gap-4">
        <div class="flex items-center gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-800 tracking-tight">Historial de Usuario</h1>
                <p class="text-sm text-gray-500 font-medium">Reportes Pendientes</p>
            </div>
        </div>

        <div class="flex flex-col items-left gap-3">
            @foreach ($historial['pendientes'] as $h)
                <button type="button" onclick="openMaterialModal({{ $h->id }}, {{ $h->info_material }})" 
                        class="group inline-flex items-center gap-2 px-3 py-1.5 bg-purple-50 hover:bg-[#7B1FA3] text-[#7B1FA3] hover:text-white border border-purple-100 rounded-xl transition-all duration-200 cursor-pointer">

                    <span class="px-1.5 py-0.5 text-[10px] font-extrabold bg-purple-200/60 group-hover:bg-white/20 text-[#7B1FA3] group-hover:text-white rounded-md transition-colors">
                        #{{ $h->id }}
                    </span>

                    <span class="text-xs font-bold">
                        {{ $h->descripcion }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    <div class="flex flex-col pb-6 mb-8 border-b border-gray-100 gap-4">
        <div class="flex items-center gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-800 tracking-tight">Historial de Usuario</h1>
                <p class="text-sm text-gray-500 font-medium">Reportes Pasados</p>
            </div>
        </div>

        <div class="flex flex-col items-lect gap-3">
            @foreach ($historial['recibidos'] as $h)
                <button type="button" onclick="openMaterialModal({{ $h->id }}, {{ $h->info_material }})" 
                        class="group inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 hover:bg-green-600 text-green-600 hover:text-white border border-green-100 rounded-xl transition-all duration-200 cursor-pointer">

                    <span class="px-1.5 py-0.5 text-[10px] font-extrabold bg-green-200/60 group-hover:bg-white/20 text-green-600 group-hover:text-white rounded-md transition-colors">
                        #{{ $h->id }}
                    </span>

                    <span class="text-xs font-bold">
                        {{ $h->descripcion }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal de Lista de Materiales -->
<div id="material-Modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeMaterialModal()"></div>
    
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative w-full max-w-md bg-white rounded-[20px] shadow-2xl overflow-hidden transition-all duration-300">
            <!-- Encabezado -->
            <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-black tracking-wider uppercase">Materiales Solicitados</h3>
                <p class="text-[14px] font-mono text-gray-400 bg-gray-50 px-2 py-1 rounded-md" id="id-solicitud">#123456</p>
            </div>

            <!-- Lista de Materiales -->
            <div class="px-6 py-6">
                <ul id="material-Lista" class="space-y-3"></ul>
            </div>

            <!-- Cerrar Material -->
            <div class="bg-gray-50 px-6 py-4 flex justify-center">
                <button type="button" onclick="closeMaterialModal()" 
                    class="px-10 py-2 bg-[#7B1FA3] text-white text-xs font-bold rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openMaterialModal(requestId, materials) {
        const modal = document.getElementById('material-Modal');
        const listContainer = document.getElementById('material-Lista');
        const idLabel = document.getElementById('id-solicitud');

        listContainer.innerHTML = '';
        idLabel.innerText = '#' + requestId;

        // Lista
        materials.forEach(item => {
            const li = document.createElement('li');
                li.className = "group flex items-center justify-between p-3.5 mb-2.5 bg-white hover:bg-[#F5F3FF] border-l-4 border-[#7B1FA3] rounded-r-xl border-gray-100 shadow-sm hover:shadow-md transition-all duration-200";
                li.innerHTML = `
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-800 group-hover:text-[#7B1FA3] transition-colors truncate">
                                ${item.nombre}
                            </p>
                        </div>
                    </div>

                    <span class="shrink-0 ml-2 px-2.5 py-1 bg-purple-50 group-hover:bg-purple-100/70 text-[#7B1FA3] text-xs font-bold rounded-lg border border-purple-100 transition-colors">
                        ${item.cantidad}
                    </span>
                `;
                listContainer.appendChild(li);
        });

        // Mostrar modal
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeMaterialModal() {
        const modal = document.getElementById('material-Modal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
</script>