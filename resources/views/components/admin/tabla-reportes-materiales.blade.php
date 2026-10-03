@props(['reportes', 'modalDescripcion', 'modalAuditorias'])
<div class="bg-white rounded-[20px] border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto no-scrollbar">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">ID</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Usuario</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Material</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Cantidad</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Descripcion</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Informacion</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Fecha</th>
                </tr>
            </thead>
            <!-- En todos los archivos que se va a mostar la informacion la parte del tbody tiene que tener ese id para que funcione el filtro de informacion -->
            <tbody id="informacion-filtrada" class="divide-y divide-gray-50">
                @foreach ($reportes as $r)
                    @php
                        $info = json_decode($r->info_usuario);
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 text-sm text-black font-medium text-center">
                            {{ $r->id }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $info->nombre }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium">{{ $info->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-black font-medium text-center">
                            {{ $r->nombre }}
                        </td>
                        <td class="px-6 py-4 text-sm text-black font-medium text-center">
                            {{ $r->cantidad }}
                        </td>
                        <td class="px-6 py-4 justify-center">
                            <div class="flex justify-center">
                                <button type="button" 
                                    wire:click="abrirModalDescripcion({{ $r->id }})"
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
                                <button wire:click="abrirModalAuditorias({{ $r->id }})" title="Ver Auditoria"
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
                            {{ $r->fecha }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if ($modalAuditorias)
    <div id="auditoria-modal" class="fixed inset-0 z-[150] overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="cerrarModalAuditorias"></div>
        
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-[20px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-[750px]" >
                <!-- Encabezado -->
                <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-black tracking-wider uppercase">Auditoria</h3>
                    <p class="text-[14px] font-mono text-gray-400 bg-gray-50 px-2 py-1 rounded-md" id="id-auditoria">#{{ $reporteSeleccionado }}</p>
                </div>

                <div id="contenedor-auditorias" class="p-4">
                    @if (count($auditorias) > 0)
                        <table class="w-full text-left border-collapse min-w-[700px]">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50/50">
                                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Usuario</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Estado</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                            @foreach ($auditorias as $auditoria)
                                @php
                                    $infoUsuario = json_decode($auditoria->info_usuario);
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div>
                                                <p class="text-sm font-bold text-gray-800">{{ $infoUsuario->nombre }}</p>
                                                <p class="text-xs text-gray-400">{{ $infoUsuario->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 text-[10px] text-center font-bold rounded-lg bg-green-50 text-green-600 border border-green-100 uppercase">
                                            {{ $auditoria->estado }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $auditoria->fecha }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="mb-4 p-4 bg-[#F7F6F8] rounded-2xl relative group transition-all cursor-default">
                            <p class="text-[11px] text-gray-700 font-bold leading-relaxed line-clamp-3">
                                No hay auditorias.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Boton de Cerrar -->
                <div class="bg-gray-50 px-6 py-5 flex justify-center">
                    <button type="button" wire:click="cerrarModalAuditorias" 
                        class="cerrar-modal-auditoria px-10 py-2 bg-[#7B1FA3] text-white text-xs font-bold rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($modalDescripcion)
    <div id="descripcion-Modal" class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="cerrarModalDescripcion"></div>
        
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-[20px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                <!-- Encabezado -->
                <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-black tracking-wider uppercase">Motivo de la Solicitud</h3>
                    <p class="text-[14px] font-mono text-gray-400 bg-gray-50 px-2 py-1 rounded-md" id="id-solicitud">#{{ $reporteSeleccionado }}</p>
                </div>

                <div class="px-6 py-8">
                    <div class="relative group">
                        <!-- Descripcion -->
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Descripción del Problema</p>
                        <div class="relative">
                            <textarea id="descripcion-solicitud" readonly 
                                class="w-full p-5 bg-gray-50 border border-gray-100 rounded-[20px] text-sm text-gray-600 focus:outline-none resize-none h-40 leading-relaxed shadow-inner"
                                placeholder="Descripcion">{{ $descripcion }}
                            </textarea>
                        </div>
                    </div>
                </div>

                <!-- Boton de Cerrar -->
                <div class="bg-gray-50 px-6 py-5 flex justify-center">
                    <button type="button" wire:click="cerrarModalDescripcion" 
                        class="px-10 py-2 bg-[#7B1FA3] text-white text-xs font-bold rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    function closeMaterialModal() {
        document.getElementById('contenedor-reportes').innerHTML = '';
        document.getElementById('numero-computadora').innerHTML = '';
        const modal = document.getElementById('reportes-modal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function closeAuditoriaModal() {
        document.getElementById('contenedor-auditorias').innerHTML = '';
        document.getElementById('id-auditoria').innerHTML = '';
        const modal = document.getElementById('auditoria-modal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function openMaterialModal(requestId, description) {
        const modal = document.getElementById('descripcion-Modal');
        const idLabel = document.getElementById('id-solicitud');
        const descField = document.getElementById('descripcion-solicitud');

        idLabel.innerText = '#' + requestId;
        descField.value = description;

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeMaterialModal() {
        const modal = document.getElementById('descripcion-Modal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
</script>