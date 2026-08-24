@props(['solicitudes'])
<div class="bg-white rounded-[20px] border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto overflow-y-auto max-h-[calc(100dvh-300px)] no-scrollbar">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <!-- Encabezados de la Tabla -->
            <thead class="sticky top-0 z-10 bg-gray-50">
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text">Alumno</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">ID Solicitud</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Laboratorio</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Materiales</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Fecha</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Acciones</th>
                </tr>
            </thead>
            
            <tbody id="contenedor-solicitudes" class="divide-y divide-gray-50">
                @foreach ($solicitudes as $solicitud)
                    @php
                        $info = json_decode($solicitud->info_usuario)
                    @endphp

                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <!-- Nombre, Correo y Grado/Grupo -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $info->nombre }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium">{{ $info->email }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium">{{ $info->grado }}° {{ $info->grupo }} - {{ $info->nombreGrupo }} - {{ $info->turno }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- ID de Solicitud -->
                        <td class="px-6 py-4 text-sm text-black text-center font-medium">
                            {{ $solicitud->id }}
                        </td>

                        <!-- Laboratorio -->
                        <td class="px-6 py-4 text-center text-black text-sm font-medium tracking-tight">
                                {{ $info->nombreLaboratorio }}
                        </td>

                        <!-- Lista de Materiales -->
                        <td class="px-6 py-4 justify-center">
                            <div class="flex justify-center">
                                <button type="button" onclick="openMaterialModal({{ $solicitud->id }}, {{ $solicitud->info_material }})" 
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
                            {{ \Carbon\Carbon::parse($solicitud->fecha)->format('d/m/Y') }}
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-3">
                                <!-- Aprobar Solicitud -->
                                <button data-id="{{ $solicitud->id }}" class="aceptada flex items-center gap-1 px-3 py-1.5 rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all text-xs font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Aprobar
                                </button>

                                <!-- Rechazar Solicitud -->
                                <button data-id="{{ $solicitud->id }}" data-idlaboratorio="{{ $info->idLaboratorio }}" data-idusuario="{{ $info->id }}" data-fecha="{{ $solicitud->fecha }}" class="rechazada flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all text-xs font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Rechazar
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
    function openMaterialModal(requestId, materiales) {
        const modal = document.getElementById('material-Modal');
        const listContainer = document.getElementById('material-Lista');
        const idLabel = document.getElementById('id-solicitud');

        listContainer.innerHTML = '';
        idLabel.innerText = '#' + requestId;

        materiales.forEach(item => {
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