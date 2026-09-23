@props(['admin'])
<aside class="w-64 bg-white h-screen flex flex-col border-r border-gray-100 shrink-0">
    <div class="p-6 flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center gap-3">
            <a href="{{ url('/seleccionar-tipo-usuario') }}">
                <h1 class="text-sm font-extrabold text-[#7B1FA3] leading-none">GAMMA</h1>
            </a>
        </div>

        <button id="cerrar-sidebar" class="md:hidden p-1 text-gray-400 hover:text-red-500 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
        @if (session('normal') && !session("bloqueado"))
            <a href="{{ route('activar.rol','normal') }}" 
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('/usuario/normal/laboratorios') ? 'bg-[#F5F3FF] text-[#7B1FA3]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <div class="relative">
                    <svg class="w-6 h-6 text-gray-500 group-hover:text-[#7B1FA3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <span class="text-sm font-semibold">Normal</span>
            </a>
        @endif

        @if (session('encargado') && !session("bloqueado"))
            <a href="{{ route('activar.rol','encargado') }}" 
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('/usuario/encargado/solicitudes-pendientes') ? 'bg-[#F5F3FF] text-[#7B1FA3]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <div class="relative">
                    <svg class="w-6 h-6 text-gray-500 group-hover:text-[#7B1FA3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <span class="text-sm font-semibold">Encargado de Area</span>
            </a>
        @endif
        
        @if (session('mantenimiento'))
            <a href="{{ route('activar.rol','mantenimiento') }}" 
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('/usuario/mantenimiento/reportes-computo') ? 'bg-[#F5F3FF] text-[#7B1FA3]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <div class="relative">
                    <svg class="w-6 h-6 text-gray-500 group-hover:text-[#7B1FA3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-sm font-semibold">Encargado de Mantenimiento</span>
            </a>
        @endif
        
    </nav>

    <!-- Nombre de Usuario y Correo -->
    <div class="p-2 border-t border-gray-100">
        <div class="flex items-center justify-between p-2 rounded-xl hover:bg-gray-50 transition-colors group">
            <a href="{{ url('/perfil') }}" class="flex flex-col items-start gap-0.5">
                <p class="text-xs font-bold text-gray-800 break-words">{{ session('nombre_usuario') }}</p>
                <p class="text-[10px] text-gray-400 truncate">{{ session('email') }}</p>
            </a>
            <!-- Cerrar Sesion -->
            <form action="{{ url('/logout') }}" method="POST" class="border-l pl-4 border-gray-100">
                @csrf
                <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const aside = document.querySelector('aside');
        const overlay = document.getElementById('sidebar-overlay');
        const btnAbrir = document.getElementById('abrir-sidebar');
        const btnCerrar = document.getElementById('cerrar-sidebar');

        const clasesResponsivas = [
            'fixed', 'inset-y-0', 'left-0', 'z-50', 
            'transform', '-translate-x-full', 'transition-transform', 
            'duration-300', 'ease-in-out', 'md:relative', 'md:translate-x-0'
        ];
        aside.classList.add(...clasesResponsivas);

        function toggleSidebar() {
            const estaEscondido = aside.classList.contains('-translate-x-full');
            
            if (estaEscondido) {
                aside.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                btnAbrir.classList.add('opacity-0', 'pointer-events-none');
                document.body.classList.add('overflow-hidden');
            } else {
                aside.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                btnAbrir.classList.remove('opacity-0', 'pointer-events-none');
                document.body.classList.remove('overflow-hidden');
            }
        }

        btnAbrir.addEventListener('click', toggleSidebar);
        btnCerrar.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    });
</script>