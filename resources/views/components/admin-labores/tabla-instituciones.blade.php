@props(['instituciones'])

<div class="bg-white rounded-[20px] border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto no-scrollbar">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="sticky top-0 z-10 bg-gray-50">
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">ID</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nombre Institucion</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tag</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Clave</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase text-center tracking-widest">Servicios</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Acciones</th>
                </tr>
            </thead>
            
            <tbody id="informacion-filtrada" class="divide-y divide-gray-50">
                @foreach ($instituciones as $institucion)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-black truncate">{{ $institucion->id }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Nombre -->
                        <td class="px-6 py-4 text-sm text-black font-medium">
                            {{ $institucion->nombre }}
                        </td>

                        <!-- Tag -->
                        <td class="px-6 py-4 text-sm text-black font-medium">
                            {{ $institucion->tag }}
                        </td>

                        <!-- Clave -->
                        <td class="px-6 py-4 text-sm text-black font-medium">
                            {{ $institucion->clave }}
                        </td>

                        <!-- Servicios -->
                        <td class="px-6 py-4 justify-center">
                            <div class="flex justify-center">
                                <button type="button" data-id="{{ $institucion->id }}"
                                    class="servicios flex items-center gap-2 text-[#7B1FA3] hover:text-white transition-colors"
                                    title="Ver Servicios">
                                    <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                    </div>
                                </button>
                            </div>
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <div class="seleccionar-registro hidden">
                                    <input type="checkbox" value="{{ $institucion->id }}" class="check-borrar">
                                </div>
                                <div class="acciones flex items-center justify-center gap-2">
                                    <!-- Editar -->
                                    <button title="Editar" class="abrir-modal-edit p-2 text-gray-400 hover:text-blue-500 transition-colors" data-id="{{ $institucion->id }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Instituciones -->
<div id="modal-servicios" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative w-full max-w-md bg-white rounded-[20px] shadow-2xl overflow-hidden transition-all duration-300">
            <!-- Encabezado -->
            <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-black tracking-wider uppercase">
                    Servicios Disponibles
                </h3>
                <button id="cerrar-modal-servicios" 
                    class="text-gray-400 hover:text-red-500 text-lg font-bold">
                    ✕
                </button>
            </div>
            <!-- Lista -->
            <div class="px-6 py-6 max-h-[300px] overflow-y-auto no-scrollbar">
                <ul id="contenido-modal-servicios" class="space-y-3 text-sm text-gray-600">
                    <!-- Aquí se insertan los laboratorios -->
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    const modalServicios = document.getElementById('modal-servicios');
    const cerrarModalServicios = document.getElementById('cerrar-modal-servicios');

    cerrarModalServicios.addEventListener('click', () => {
        modalServicios.classList.add('hidden');
    }); 
</script>