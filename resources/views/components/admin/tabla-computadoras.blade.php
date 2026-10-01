@props(['computadoras','modalReportes','modalAuditorias'])
<div class="bg-white rounded-[20px] border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto no-scrollbar">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="sticky top-0 z-10 bg-gray-50">
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">No. Computadora</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Estado</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Reportes</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Especificaciones</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Cambiar Estado</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Reemplazar Equipo</th>
                </tr>
            </thead>
            <!-- En todos los archivos que se va a mostar la informacion la parte del tbody tiene que tener ese id para que funcione el filtro de informacion -->
            <tbody id="informacion-filtrada" class="divide-y divide-gray-50">
                @foreach ($computadoras as $computadora)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 text-sm text-black font-medium text-center">{{ $computadora->numero_computadora }}</td>
                        <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg {{ ($computadora->estado == 'activo')?'bg-green-50 text-green-600 border border-green-100':'bg-red-50 text-red-600 border border-red-100' }} w-fit">
                                {{ $computadora->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                <button wire:click="reportesComputadora({{ $computadora->id }},{{ $computadora->numero_computadora }})"
                                    class="flex items-center gap-2 text-[#7B1FA3] hover:text-white">
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
                                <button wire:click="especificaciones({{ $computadora->id }} ,{{ $computadora->numero_computadora }})"
                                    class="flex items-center gap-2 text-[#7B1FA3] hover:text-white">
                                    <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                    </div>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center">
                                <button wire:confirm="¿Deseas cambiar el estado del equipo de cómputo?" wire:click="cambiarEstado({{ $computadora->id }})" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-black hover:text-[#7B1FA3] hover:bg-purple-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7h-9M20 7l-3-3M20 7l-3 3M4 17h9M4 17l3 3M4 17l3-3" />
                                    </svg>
                                    <span class="font-medium">Cambiar estado</span>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                <button wire:confirm="¿Deseas reemplazar el equipo de cómputo?" wire:click="reemplazarComputadora({{ $computadora->id }})" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-black hover:text-[#7B1FA3] hover:bg-purple-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M23 4v6h-6M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
                                    </svg>
                                    <span class="font-medium">Reemplazar equipo</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de Descripcion -->
@if ($modalReportes)
    <div class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="cerrarModalReportes"></div>
        
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative w-full max-w-md bg-white rounded-[20px] shadow-2xl overflow-hidden transition-all duration-300">
                <!-- Encabezado -->
                <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-black tracking-wider uppercase">Reportes</h3>
                    <p class="text-[14px] font-mono text-gray-400 bg-gray-50 px-2 py-1 rounded-md">#{{ $computadoraSeleccionada }}</p>
                </div>

                <div class="p-4">
                    @forelse ($reportesDeComputadora as $reporte)
                        <div wire:click="auditoriasReporte({{ $reporte->id }})" class="mb-4 p-4 bg-[#F7F6F8] rounded-2xl border-2 border-red-200 relative group hover:shadow-md hover:border-red-600 transition-all cursor-default">
                            <p class="text-[11px] text-gray-700 font-bold leading-relaxed line-clamp-3 uppercase">
                                {{ $reporte->tipo }}
                            </p>
                            <p class="text-[11px] text-gray-500 font-bold leading-relaxed line-clamp-3">
                                {{ $reporte->descripcion }}
                            </p>
                        </div>
                    @empty
                        <div class="mb-4 p-4 bg-[#F7F6F8] rounded-2xl relative group transition-all cursor-default">
                            <p class="text-[11px] text-gray-700 font-bold leading-relaxed line-clamp-3">
                                No hay reportes.
                            </p>
                        </div>
                    @endforelse
                </div>

                <!-- Boton de Cerrar -->
                <div class="bg-gray-50 px-6 py-5 flex justify-center">
                    <button type="button" wire:click="cerrarModalReportes" 
                        class="px-10 py-2 bg-[#7B1FA3] text-white text-xs font-bold rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($modalAuditorias)
    <div class="fixed inset-0 z-[150] overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="cerrarModalAuditoria"></div>
        
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-[20px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-[750px]" >
                <!-- Encabezado -->
                <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-black tracking-wider uppercase">Auditoria</h3>
                    <p class="text-[14px] font-mono text-gray-400 bg-gray-50 px-2 py-1 rounded-md">#{{ $reporteSeleccionado }}</p>
                </div>

                <div class="p-4 w-full overflow-x-auto no-scrollbar">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/50">
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Usuario</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Estado</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($auditoriaDeReporte as $auditoria)
                                @php
                                    $info = json_decode($auditoria->info_usuario);
                                @endphp

                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div>
                                                <p class="text-sm font-bold text-gray-800">{{ $info->nombre }}</p>
                                                <p class="text-xs text-gray-400">{{ $info->email }}</p>
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
                            @empty
                                <div class="mb-4 p-4 bg-[#F7F6F8] rounded-2xl relative group transition-all cursor-default">
                                    <p class="text-[11px] text-gray-700 font-bold leading-relaxed line-clamp-3">
                                        No hay auditorias.
                                    </p>
                                </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Boton de Cerrar -->
                <div class="bg-gray-50 px-6 py-5 flex justify-center">
                    <button type="button" wire:click="cerrarModalAuditoria" 
                        class="px-10 py-2 bg-[#7B1FA3] text-white text-xs font-bold rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@include('admin.modal-especificaciones')