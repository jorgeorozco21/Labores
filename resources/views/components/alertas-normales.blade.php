<div class="fixed top-1 left-1/2 -translate-x-1/2 md:left-[57%] md:-translate-x-1/2 z-[200] w-full max-w-5xl px-4 space-y-3 pointer-events-none">

    <!-- Alerta de Error de Sesión -->
    @if (session('error'))
        <div class="alerta-temporal bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-2xl text-sm shadow-sm transition-all duration-500 transform" role="alert">
            <div class="flex items-center gap-3 mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="font-bold">Se detectaron errores:</p>
            </div>
            <ul class="list-disc list-inside opacity-80 ml-8">
                <li>{{ session('error') }}</li>
            </ul>
        </div>
    @endif
    
    <!-- Alerta de Éxito -->
    @if (session('success'))
        <div wire:key="success-{{ md5(session('success')) }}"
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 3000)" 
            x-show="show"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2" 
            class="pointer-events-auto bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl text-sm font-bold shadow-sm transition-all duration-500 transform flex items-center gap-3" role="alert">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
</div>