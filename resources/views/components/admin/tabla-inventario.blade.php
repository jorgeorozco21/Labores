@props(['inventarios'])
<div class="bg-white rounded-[20px] border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto no-scrollbar">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="sticky top-0 z-10 bg-gray-50">
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Material</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Laboratorio</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Cantidad Disponible</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Cantidad Total</th>
                    @if ($laboratorio)
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Acciones</th>
                    @endif
                </tr>
            </thead>
            <!-- En todos los archivos que se va a mostar la informacion la parte del tbody tiene que tener ese id para que funcione el filtro de informacion -->
            <tbody id="informacion-filtrada" class="divide-y divide-gray-50">
                @foreach ($inventarios as $inventario)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 text-sm text-black font-medium text-center">{{ $inventario->nombreMaterial }}</td>
                        <td class="px-6 py-4 text-sm text-black font-medium text-center">{{ $inventario->nombreLaboratorio }}</td>
                        <td class="px-6 py-4 text-sm text-black font-medium text-center">{{ $inventario->cantidad_disponible }}</td>
                        <td class="px-6 py-4 text-sm text-black font-medium text-center">{{ $inventario->cantidad_total }}</td>
                        @if ($laboratorio)
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    @if ($opcionesBorrado)
                                        <div class="seleccionar-registro">
                                            <input type="checkbox" wire:model.live="idsSeleccionados" value="{{ $inventario->id }}" class="check-borrar">
                                        </div>
                                    @else
                                        <div class="acciones flex items-center justify-center gap-2">
                                            <!-- Editar -->
                                            <button wire:click="editar({{ $inventario->id }})" class="p-2 text-gray-400 hover:text-blue-500 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </button>
                                            <!-- Eliminar -->
                                            <button type="button" wire:click="eliminar({{ $inventario->id }})" wire:confirm="¿Deseas borrar este inventario?" value="Borrar" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $inventarios->withQueryString()->links() }}
    </div>
</div>