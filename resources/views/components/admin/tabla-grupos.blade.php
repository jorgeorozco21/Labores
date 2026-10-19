@props(['grupos','modalLaboratorios'])
<div class="bg-white rounded-[20px] border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto no-scrollbar">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="sticky top-0 z-10 bg-gray-50">
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Nombre</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Tipo de Grupo</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Laboratorios</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Acciones</th>
                </tr>
            </thead>
            <!-- En todos los archivos que se va a mostar la informacion la parte del tbody tiene que tener ese id para que funcione el filtro de informacion -->
            <tbody id="informacion-filtrada" class="divide-y divide-gray-50">
                @foreach ($grupos as $grupo)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 text-sm text-black font-medium text-center">{{ $grupo->nombre }}</td>
                        <td class="px-6 py-4 text-sm text-black font-medium text-center">{{ $grupo->tipo_grupo }}</td>
                        <td class="px-6 py-4 justify-center">
                            <div class="flex justify-center">
                                <button wire:click="mostrarLaboratorios({{ $grupo->id }})" class="ver flex items-center gap-2 text-[#7B1FA3] hover:text-white transition-colors">
                                    <div class="p-1.5 bg-purple-100 hover:bg-[#7B1FA3] rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1" />
                                        </svg>
                                    </div>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                @if ($opcionesBorrado)
                                    <div class="seleccionar-registro">
                                        <input type="checkbox" wire:model.live="idsSeleccionados" value="{{ $grupo->id }}" class="check-borrar">
                                    </div>
                                @else
                                    <div class="acciones flex items-center justify-center gap-2">
                                        <button wire:click="editar({{ $grupo->id }})" class="abrir-modal-edit p-2 text-gray-400 hover:text-blue-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        <button type="submit" wire:click="eliminar({{ $grupo->id }})" wire:confirm="¿Deseas borra el grupo?" value="Borrar" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $grupos->withQueryString()->links() }}
    </div>
</div>

@if ($modalLaboratorios)
    <!-- Modal Laboratorios -->
    <div id="modal-laboratorios" class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="cerrarModalLaboratorios"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative w-full max-w-md bg-white rounded-[20px] shadow-2xl overflow-hidden transition-all duration-300">
                <!-- Encabezado -->
                <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-black tracking-wider uppercase">
                        Laboratorios
                    </h3>
                    <button id="cerrar-modal-laboratorios" wire:click="cerrarModalLaboratorios"
                        class="text-gray-400 hover:text-red-500 text-lg font-bold">
                        ✕
                    </button>
                </div>
                <!-- Lista -->
                <div class="px-6 py-6 max-h-[300px] overflow-y-auto no-scrollbar">
                    <ul id="contenido-modal-laboratorios" class="space-y-3 text-sm text-gray-600">
                        <!-- Aquí se insertan los laboratorios -->
                        @foreach ($laboratoriosMostrar as $laboratorio)
                            <li class="group flex items-center justify-between p-3.5 mb-2.5 bg-white hover:bg-[#F5F3FF] border-l-4 border-[#7B1FA3] rounded-r-xl border-gray-100 shadow-sm hover:shadow-md transition-all duration-200">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-800 group-hover:text-[#7B1FA3] transition-colors truncate">
                                            {{ $laboratorio->nombre }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif