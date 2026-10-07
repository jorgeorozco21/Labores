@props(['nombreFuncion','nombreFuncionCerrar','subtitulo','modalCargaMasiva'])

@if ($modalCargaMasiva)
    <div class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
        <div id="content-carga" class="relative bg-white w-full max-w-md p-8 rounded-[30px] shadow-2xl transform transition-all duration-300 overflow-hidden">
            <button wire:click="{{ $nombreFuncionCerrar }}" id="cerrar-modal-carga" class="absolute top-6 right-6 text-gray-400 hover:text-red-500 transition-colors font-bold text-xl">✕</button>
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-[#7B1FA3] shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Carga Masiva</h2>
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">{{ $subtitulo }}</p>
                </div>
            </div>
            <!-- Descripcion Carga Masiva -->
            <div class="mb-2 bg-gray-50 border border-gray-100 p-4 rounded-2xl">
                <p class="text-[11px] text-gray-500 leading-relaxed">
                    La <strong class="text-gray-700">Carga Masiva</strong> es una herramienta diseñada para importar grandes volúmenes de datos mediante un solo archivo. 
                    En lugar de registrar cada cuenta manualmente, puedes subir una plantilla en formato <strong class="text-gray-700">.xlsx</strong> o con toda la información y el sistema la procesará automáticamente.
                </p>
            </div>
            <!-- Form Carga Masiva -->
            <div class="max-h-[80vh] overflow-y-auto">
                <div class="space-y-6">
                    <!-- Descripcion y Plantilla -->
                    <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl">
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Sube un archivo <strong class="text-gray-700">.xlsx</strong> con el formato establecido. Si no tienes la plantilla, descárgala aquí:
                        </p>
                        <button type="button" wire:click="archivoCarga" class="inline-flex items-center gap-2 mt-3 text-[#7B1FA3] text-xs font-bold hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Descargar Plantilla
                        </button>
                    </div>
                    <form wire:submit.prevent="{{ $nombreFuncion }}" class="space-y-4">
                        @include('admin.input-carga-masiva')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif