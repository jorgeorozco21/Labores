@props(['admin'])

<aside id="sidebar" class="fixed md:relative inset-y-0 left-0 z-50 w-64 bg-white h-dvh flex flex-col border-r border-gray-100 transform sidebar-no-transition">
    <button id="toggle-collapse" class="hidden md:flex absolute top-1/2 -right-4 -translate-y-1/2 z-50 items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 shadow-md hover:bg-[#7B1FA3] hover:text-white transition-all duration-300">
        <svg id="icono-flecha" class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <div class="relative flex items-center justify-center w-full h-20 overflow-hidden">
        <a href="{{ url('/seleccionar-tipo-usuario') }}" class="flex items-center justify-center">
            <img id="logo-grande" src="{{ asset('images/logos/labores_logo_horizontal_morado.webp') }}" class="absolute h-16 left-5 opacity-100 scale-100 transition-all duration-300 ease-in-out" alt="Logo">
            <img id="logo-pequeno" src="{{ asset('images/logos/labores_icono_morado.webp') }}" class="absolute h-10 w-10 opacity-0 scale-75 transition-all duration-300 ease-in-out" alt="Icono">
        </a>

        <button id="cerrar-sidebar" class="md:hidden absolute right-4 p-1 text-gray-400 hover:text-red-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
        @if (request()->is('*encargado*'))
            <p class="sidebar-text font-bold text-xs text-gray-400 uppercase tracking-wider pl-4 my-2 transition-all duration-300">Prestamos</p>
            <a href="{{ url('/usuario/encargado/solicitudes-pendientes') }}" title="Solicitudes Pendientes"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('usuario/encargado/solicitudes-pendientes') ? 'bg-[#F5F3FF] text-[#7B1FA3]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->is('usuario/encargado/solicitudes-pendientes') ? 'text-[#7B1FA3]' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="sidebar-text text-sm font-semibold">Solicitudes Pendientes</span>
            </a>

            <a href="{{ url('/usuario/encargado/solicitudes-aceptadas') }}" title="Solicitudes Aceptadas"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('usuario/encargado/solicitudes-aceptadas') ? 'bg-[#F5F3FF] text-[#7B1FA3]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->is('usuario/encargado/solicitudes-aceptadas') ? 'text-[#7B1FA3]' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="sidebar-text text-sm font-semibold">Solicitudes Aceptadas</span>
            </a>

            <p class="sidebar-text font-bold text-xs text-gray-400 uppercase tracking-wider pl-4 my-2 transition-all duration-300">Computo</p>
            <a href="{{ url('/usuario/encargado/solicitudes-pendientes-computo') }}" title="Solicitudes Pendientes"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('usuario/encargado/solicitudes-pendientes-computo') ? 'bg-[#F5F3FF] text-[#7B1FA3]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->is('solicitudes-pendientes-computo') ? 'text-[#7B1FA3]' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="sidebar-text text-sm font-semibold">Solicitudes Pendientes</span>
            </a>

            <a href="{{ url('/usuario/encargado/solicitudes-aceptadas-computo') }}" title="Solicitudes Aceptadas"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('usuario/encargado/solicitudes-aceptadas-computo') ? 'bg-[#F5F3FF] text-[#7B1FA3]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->is('usuario/encargado/solicitudes-aceptadas-computo') ? 'text-[#7B1FA3]' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="sidebar-text text-sm font-semibold">Solicitudes Aceptadas</span>
            </a>

            <p class="sidebar-text font-bold text-xs text-gray-400 uppercase tracking-wider pl-4 my-2 transition-all duration-300">Reportes</p>
            <a href="{{ url('/usuario/encargado/reportes-materiales') }}" title="Reportes"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('usuario/encargado/reportes-materiales') ? 'bg-[#F5F3FF] text-[#7B1FA3]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->is('usuario/encargado/reportes-materiales') ? 'text-[#7B1FA3]' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M9 8h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />                    
                </svg>
                <span class="sidebar-text text-sm font-semibold">Reportes</span>
            </a>
        @endif
        
        @if (request()->is('*mantenimiento*'))
            <p class="sidebar-text font-bold text-xs text-gray-400 uppercase tracking-wider pl-4 my-2 transition-all duration-300">Reportes</p>
            <a href="{{ url('/usuario/mantenimiento/reportes-computo') }}" title="Reportes de Computo"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('usuario/mantenimiento/reportes-computo') ? 'bg-[#F5F3FF] text-[#7B1FA3]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->is('usuario/mantenimiento/reportes-computo') ? 'text-[#7B1FA3]' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="sidebar-text text-sm font-semibold">Reportes de Computo</span>
            </a>

            <a href="{{ url('/usuario/mantenimiento/reportes-materiales') }}" title="Reportes de Materiales"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('usuario/mantenimiento/reportes-materiales') ? 'bg-[#F5F3FF] text-[#7B1FA3]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->is('usuario/mantenimiento/reportes-materiales') ? 'text-[#7B1FA3]' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z" />
                </svg>
                <span class="sidebar-text text-sm font-semibold">Reportes de Materiales</span>
            </a>
        @endif
    </nav>

    <div class="p-2 border-t border-gray-100">
        <div id="perfil-sidebar" class="flex items-center justify-between p-2 rounded-xl hover:bg-gray-50 transition-all duration-300 group">
            <a href="{{ url('/perfil') }}" class="flex items-center gap-3 pr-1 min-w-0">
                <img src="https://ui-avatars.com/api/?name=Encargado&background=6B7280&color=fff"
                    class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm shrink-0" alt="User">

                <div id="info-usuario" class="leading-tight min-w-0 transition-all duration-300">
                    <p class="text-xs font-bold text-gray-800 break-words">
                        Encargado {{ (request()->is('*encargado*')) ? 'Area' : 'Mantenimiento' }}
                    </p>
                    <p class="text-[10px] text-gray-400 truncate" title="{{ session('email') }}">
                        {{ session('email') }}
                    </p>
                </div>
            </a>
            
            <form id="logout-sidebar" action="{{ url('/logout') }}" method="POST" class="border-l pl-2 border-gray-100 transition-all duration-300" title="Cerrar Sesión">
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

<style>
    .sidebar-no-transition,
    .sidebar-no-transition * {
        transition: none !important;
    }

    .sidebar-ready {
        transition: width 0ms ease, transform 0ms ease;
    }
</style>

<script>
(function () {

    const aside = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const btnAbrir = document.getElementById('abrir-sidebar');
    const btnCerrar = document.getElementById('cerrar-sidebar');

    const toggleCollapse = document.getElementById('toggle-collapse');

    const infoUsuario = document.getElementById('info-usuario');
    const logoutSidebar = document.getElementById('logout-sidebar');
    const perfilSidebar = document.getElementById('perfil-sidebar');

    const textos = document.querySelectorAll('.sidebar-text');

    const logoGrande = document.getElementById('logo-grande');
    const logoPequeno = document.getElementById('logo-pequeno');

    let colapsado = localStorage.getItem('sidebarColapsado') === 'true';

    function abrirSidebar() {
        aside.classList.remove('-translate-x-full');

        if (overlay) {
            overlay.classList.remove('hidden');
        }

        if (btnAbrir) {
            btnAbrir.classList.add('opacity-0', 'pointer-events-none');
        }

        document.body.classList.add('overflow-hidden');
    }

    function cerrarSidebar() {
        aside.classList.add('-translate-x-full');

        if (overlay) {
            overlay.classList.add('hidden');
        }

        if (btnAbrir) {
            btnAbrir.classList.remove('opacity-0', 'pointer-events-none');
        }

        document.body.classList.remove('overflow-hidden');
    }

    function toggleSidebar() {
        const oculto = aside.classList.contains('-translate-x-full');

        if (oculto) {
            abrirSidebar();
        } else {
            cerrarSidebar();
        }
    }

    if (btnAbrir) {
        btnAbrir.addEventListener('click', toggleSidebar);
    }

    if (btnCerrar) {
        btnCerrar.addEventListener('click', cerrarSidebar);
    }

    if (overlay) {
        overlay.addEventListener('click', cerrarSidebar);
    }

    function colapsarSidebar(guardar = true) {
        aside.classList.remove('w-64');
        aside.classList.add('w-20');

        textos.forEach(texto => {
            texto.classList.add('hidden');
        });

        document.querySelectorAll('nav a').forEach(link => {
            link.classList.add('justify-center');
        });

        if (infoUsuario) {
            infoUsuario.classList.add('hidden');
        }

        if (logoutSidebar) {
            logoutSidebar.classList.remove('border-l', 'pl-2');
        }

        const iconoFlecha = document.getElementById('icono-flecha');

        if (iconoFlecha) {
            iconoFlecha.classList.add('rotate-180');
        }

        colapsado = true;

        if (guardar) {
            localStorage.setItem('sidebarColapsado', 'true');
        }

        if (logoGrande) {
            logoGrande.classList.remove('opacity-100', 'scale-100');
            logoGrande.classList.add('opacity-0', 'scale-75');
        }

        if (logoPequeno) {
            logoPequeno.classList.remove('opacity-0', 'scale-75');
            logoPequeno.classList.add('opacity-100', 'scale-100');
        }

        if (perfilSidebar) {
            perfilSidebar.classList.remove('flex-row', 'justify-between');
            perfilSidebar.classList.add(
                'flex-col',
                'justify-center',
                'gap-2'
            );
        }
    }

    function expandirSidebar(guardar = true) {
        aside.classList.remove('w-20');
        aside.classList.add('w-64');

        textos.forEach(texto => {
            texto.classList.remove('hidden');
        });

        document.querySelectorAll('nav a').forEach(link => {
            link.classList.remove('justify-center');
        });

        if (infoUsuario) {
            infoUsuario.classList.remove('hidden');
        }

        if (logoutSidebar) {
            logoutSidebar.classList.add('border-l', 'pl-2');
        }

        const iconoFlecha = document.getElementById('icono-flecha');

        if (iconoFlecha) {
            iconoFlecha.classList.remove('rotate-180');
        }

        colapsado = false;

        if (guardar) {
            localStorage.setItem('sidebarColapsado', 'false');
        }

        if (logoGrande) {
            logoGrande.classList.remove('opacity-0', 'scale-75');
            logoGrande.classList.add('opacity-100', 'scale-100');
        }

        if (logoPequeno) {
            logoPequeno.classList.remove('opacity-100', 'scale-100');
            logoPequeno.classList.add('opacity-0', 'scale-75');
        }

        if (perfilSidebar) {
            perfilSidebar.classList.remove(
                'flex-col',
                'justify-center',
                'gap-2'
            );

            perfilSidebar.classList.add(
                'flex-row',
                'justify-between'
            );
        }
    }

    if (toggleCollapse) {
        toggleCollapse.addEventListener('click', () => {

            if (window.innerWidth < 768) {
                return;
            }

            if (colapsado) {
                expandirSidebar(true);
            } else {
                colapsarSidebar(true);
            }
        });

    }

    function manejarResize() {

        if (window.innerWidth < 768) {

            aside.classList.remove('w-20');
            aside.classList.add('w-64');

            cerrarSidebar();

        } else {
            aside.classList.remove('-translate-x-full');

            if (overlay) {
                overlay.classList.add('hidden');
            }

            document.body.classList.remove('overflow-hidden');

            if (colapsado) {
                colapsarSidebar(false);
            } else {
                expandirSidebar(false);
            }
        }
    }

    // Desactivar temporalmente las transiciones
    aside.classList.add('sidebar-no-transition');

    manejarResize();

    requestAnimationFrame(() => {
        aside.classList.remove('sidebar-no-transition');
        aside.classList.add('sidebar-ready');
    });

    window.addEventListener('resize', manejarResize);
})();
</script>