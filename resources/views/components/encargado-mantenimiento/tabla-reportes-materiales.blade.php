@props(['reportes'])
<div class="bg-white rounded-[20px] border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto overflow-y-auto max-h-[calc(100dvh-150px)] no-scrollbar">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <!-- Encabezado de la Tabla -->
            <thead class="sticky top-0 z-10 bg-gray-50">
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Nombre Material</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Cantidad</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Laboratorio</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Descripcion</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Fecha</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Estado actual</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Proximo estado</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Acciones</th>
                </tr>
            </thead>
            
            <tbody id="contenedor-reportes" class="divide-y divide-gray-50">
                @foreach ($reportes as $reporte)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 text-sm text-black text-center font-medium">
                            {{ $reporte->nombre }}
                        </td>

                        <td class="px-6 py-4 text-sm text-black text-center font-medium">
                            {{ $reporte->cantidad }}
                        </td>

                        <td class="px-6 py-4 text-center text-black text-sm font-medium tracking-tight">
                            {{ $reporte->nombreLaboratorio }}
                        </td>

                        <!-- Descripcion -->
                        <td class="px-6 py-4 justify-center">
                            <div class="flex justify-center">
                                <button type="button" onclick="openMaterialModal('{{ $reporte->id }}', '{{ $reporte->descripcion }}')" 
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
                            {{ \Carbon\Carbon::parse($reporte->fecha)->format('d/m/Y') }}
                        </td>

                        <!-- Estado del Reporte -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center">
                                <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-bold rounded-lg border border-green-100 uppercase">
                                    {{ ($reporte->estado == null)?'espera':$reporte->estado }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center">
                                @if ($reporte->estado == null || $reporte->estado == 'reprogramado')
                                    <span class="px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-bold rounded-lg border border-orange-100 uppercase">
                                        En proceso
                                    </span>
                                @endif
                                @if ($reporte->estado == 'en proceso')
                                    <span class="px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-bold rounded-lg border border-orange-100 uppercase">
                                        Reparado
                                    </span>
                                @endif

                                <button data-estado="{{ ($reporte->estado == null || $reporte->estado == 'reprogramado')?'en proceso':'reparado' }}" data-id="{{ $reporte->id }}"
                                    class="cambiar px-3 py-1 bg-[#7B1FA3] text-white rounded-xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98] ml-2"
                                    title="Guardar cambio">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if ($reporte->estado == 'en proceso')
                                <div class="flex justify-center">
                                    <button data-id="{{ $reporte->id }}" data-estado="sin reparacion" data-inventario="{{ $reporte->id_inventario }}" data-cantidad="{{ $reporte->cantidad }}"
                                        class="reportar flex items-center gap-1 px-3 py-1 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all text-xs font-bold">
                                        Sin Reparacion
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de Descripcion -->
<div id="descripcion-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeMaterialModal()"></div>
    
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative w-full max-w-md bg-white rounded-[20px] shadow-2xl overflow-hidden transition-all duration-300">
            <!-- Encabezado -->
            <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-black tracking-wider uppercase">Motivo del Mantenimiento</h3>
            </div>

            <!-- Descripcion -->
            <div class="px-6 py-8">
                <div class="relative group">
                    <p class="text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Descripción del Problema</p>
                    <div class="relative">
                        <textarea id="descripcion-solicitud" readonly 
                            class="w-full p-5 bg-gray-50 border border-gray-100 rounded-[20px] text-sm text-gray-600 focus:outline-none resize-none h-40 leading-relaxed shadow-inner"
                            placeholder="Descripcion">
                        </textarea>
                    </div>
                </div>
            </div>

            <!-- Boton de Cerrar -->
            <div class="bg-gray-50 px-6 py-5 flex justify-center">
                <button type="button" onclick="closeMaterialModal()" 
                    class="px-10 py-2 bg-[#7B1FA3] text-white text-xs font-bold rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openMaterialModal(requestId, description) {
        const modal = document.getElementById('descripcion-modal');
        const descField = document.getElementById('descripcion-solicitud');

        descField.value = description;

        // Mostrar modal
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeMaterialModal() {
        const modal = document.getElementById('descripcion-modal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
</script>