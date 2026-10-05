{{-- Verificamos si la propiedad pública $erroresExcel de tu componente Livewire tiene elementos --}}
@if (!empty($erroresExcel))
    <div id="modalErroresExcel" class="fixed inset-0 z-[200] flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        
        <div class="bg-red-50 w-full max-w-2xl mx-4 rounded-2xl shadow-xl p-8 relative">

            <!-- Título -->
            <div class="flex items-center gap-3 mb-4 text-red-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="text-lg font-bold">Errores en Carga Masiva</h2>
            </div>

            <!-- Lista de errores -->
            <ul class="scroll-rojo list-disc list-inside text-sm text-red-600 max-h-80 overflow-y-auto space-y-1">
                @foreach ($erroresExcel as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <!-- Botón cerrar -->
            <div class="mt-6 text-center">
                <button wire:click="$set('erroresExcel', [])" 
                    class="px-10 py-2 bg-red-600 text-white text-xs font-bold rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-100 active:scale-[0.98]">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
@endif