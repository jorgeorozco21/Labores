<div class="fixed top-1 left-1/2 -translate-x-1/2 md:left-[57%] md:-translate-x-1/2 z-[100] w-full max-w-5xl px-4 space-y-3">
    
    @if ($errors->any())
        <div class="alerta-temporal bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-2xl text-sm shadow-sm transition-all duration-500 transform" role="alert">
            <div class="flex items-center gap-3 mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="font-bold">Se detectaron errores:</p>
            </div>
            <ul class="list-disc list-inside opacity-80 ml-8">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
    
    @if (session('success'))
        <div class="alerta-temporal bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl text-sm font-bold shadow-sm transition-all duration-500 transform flex items-center gap-3" role="alert">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
</div>

<script>
    document.querySelectorAll('.alerta-temporal').forEach(alerta => {
        setTimeout(() => {
            alerta.style.opacity = '0';
            alerta.style.transform = 'translateY(-10px)';
            setTimeout(() => alerta.remove(), 500);
        }, 3000);
    });
</script>