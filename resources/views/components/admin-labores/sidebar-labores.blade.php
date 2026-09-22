@props(['admin'])

<aside id="sidebar" class="fixed md:relative inset-y-0 left-0 z-50 w-64 bg-white h-dvh flex flex-col border-r border-gray-100 transform sidebar-no-transition">
        <button id="toggle-collapse" class="hidden md:flex absolute top-1/2 -right-4 -translate-y-1/2 z-50 items-center justify-center
            w-8 h-8 rounded-full bg-white border border-gray-200 shadow-md hover:bg-[#7B1FA3] hover:text-white transition-all duration-300">
        <svg id="icono-flecha" class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>
    <div class="relative flex items-center justify-center w-full h-20 overflow-hidden">
        <div class="flex items-center justify-center">
            <img id="logo-grande" src="{{ asset('images/logos/labores_logo_horizontal_morado.webp') }}" class="absolute h-16 left-5 opacity-100 scale-100 transition-all duration-300 ease-in-out" alt="Logo">
            <img id="logo-pequeno" src="{{ asset('images/logos/labores_icono_morado.webp') }}" class="absolute h-10 w-10 opacity-0 scale-75 transition-all duration-300 ease-in-out" alt="Icono">
        </div>

        <button id="cerrar-sidebar" class="md:hidden absolute right-4 p-1 text-gray-400 hover:text-red-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
        <a href="{{ url('/labores/instituciones') }}" title="Dashboard" 
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('labores/instituciones') ? 'bg-[#F5F3FF] text-[#7B1FA3]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('labores/instituciones') ? 'text-[#7B1FA3]' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10" />
            </svg>
            <span class="sidebar-text text-sm font-semibold">Instituciones</span>
        </a>

        <a href="{{ url('/labores/usuarios') }}" title="Usuarios"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('labores/usuarios') ? 'bg-[#F5F3FF] text-[#7B1FA3]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('labores/usuarios') ? 'text-[#7B1FA3]' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="sidebar-text text-sm font-semibold">Usuarios</span>
        </a>

    </nav>
    
    <div class="p-2 border-t border-gray-100">
        <div id="perfil-sidebar" class="flex items-center justify-between p-2 rounded-xl hover:bg-gray-50 transition-all duration-300 group">
            <a href="{{ url('/perfil') }}"
                class="flex items-center gap-3 pr-1 min-w-0">
                <img src="https://ui-avatars.com/api/?name={{ $admin->nombre_usuario }}&background=6B7280&color=fff"
                    class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm shrink-0" alt="User">

                <div id="info-usuario" class="leading-tight min-w-0 transition-all duration-300">
                    <p class="text-xs font-bold text-gray-800 break-words">
                        {{ $admin->nombre_usuario }}
                    </p>

                    <p class="text-[10px] text-gray-400 truncate" title="{{ $admin->email }}">
                        {{ $admin->email }}
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

    .sidebar-transition {
        transition: transform 300ms ease, width 300ms ease;
    }
</style>

<script>
(function () {

    const aside = document.getElementById('sidebar');

    const overlay = document.getElementById('sidebar-overlay');
    const btnAbrir = document.getElementById('abrir-sidebar');
    const btnCerrar = document.getElementById('cerrar-sidebar');

    const toggleCollapse = document.getElementById('toggle-collapse');
    const iconoFlecha = document.getElementById('icono-flecha');

    const infoUsuario = document.getElementById('info-usuario');
    const logoutSidebar = document.getElementById('logout-sidebar');
    const perfilSidebar = document.getElementById('perfil-sidebar');

    const textos = document.querySelectorAll('.sidebar-text');

    const logoGrande = document.getElementById('logo-grande');
    const logoPequeno = document.getElementById('logo-pequeno');

    let colapsado = localStorage.getItem('sidebarColapsado') === 'true';

    let sidebarAbierto = localStorage.getItem('sidebarAbierto') !== 'false';

    function abrirSidebar(animar = true) {
        if (animar) {
            aside.classList.add('sidebar-transition');
        } else {
            aside.classList.remove('sidebar-transition');
        }

        aside.classList.remove('-translate-x-full');

        if (overlay) {
            overlay.classList.remove('hidden');
        }

        if (btnAbrir) {
            btnAbrir.classList.add(
                'opacity-0',
                'pointer-events-none'
            );
        }

        document.body.classList.add('overflow-hidden');

        sidebarAbierto = true;

        localStorage.setItem(
            'sidebarAbierto',
            'true'
        );
    }

    function cerrarSidebar(animar = true) {
        if (animar) {
            aside.classList.add('sidebar-transition');
        } else {
            aside.classList.remove('sidebar-transition');
        }

        aside.classList.add('-translate-x-full');

        if (overlay) {
            overlay.classList.add('hidden');
        }

        if (btnAbrir) {
            btnAbrir.classList.remove(
                'opacity-0',
                'pointer-events-none'
            );
        }

        document.body.classList.remove('overflow-hidden');

        sidebarAbierto = false;

        localStorage.setItem(
            'sidebarAbierto',
            'false'
        );
    }

    function toggleSidebar() {
        const oculto = aside.classList.contains('-translate-x-full');

        if (oculto) {
            abrirSidebar(true);
        } else {
            cerrarSidebar(true);
        }
    }

    if (btnAbrir) {
        btnAbrir.addEventListener(
            'click',
            toggleSidebar
        );
    }

    if (btnCerrar) {
        btnCerrar.addEventListener(
            'click',
            () => cerrarSidebar(true)
        );
    }

    if (overlay) {
        overlay.addEventListener(
            'click',
            () => cerrarSidebar(true)
        );
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
            logoutSidebar.classList.remove(
                'border-l',
                'pl-2'
            );
        }

        if (perfilSidebar) {
            perfilSidebar.classList.remove(
                'flex-row',
                'justify-between'
            );

            perfilSidebar.classList.add(
                'flex-col',
                'justify-center',
                'gap-2'
            );
        }

        if (iconoFlecha) {
            iconoFlecha.classList.add(
                'rotate-180'
            );
        }

        if (logoGrande) {
            logoGrande.classList.remove(
                'opacity-100',
                'scale-100'
            );

            logoGrande.classList.add(
                'opacity-0',
                'scale-75'
            );
        }

        if (logoPequeno) {
            logoPequeno.classList.remove(
                'opacity-0',
                'scale-75'
            );

            logoPequeno.classList.add(
                'opacity-100',
                'scale-100'
            );
        }

        colapsado = true;

        if (guardar) {
            localStorage.setItem(
                'sidebarColapsado',
                'true'
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
            logoutSidebar.classList.add(
                'border-l',
                'pl-2'
            );
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

        if (iconoFlecha) {
            iconoFlecha.classList.remove(
                'rotate-180'
            );
        }

        if (logoGrande) {
            logoGrande.classList.remove(
                'opacity-0',
                'scale-75'
            );

            logoGrande.classList.add(
                'opacity-100',
                'scale-100'
            );
        }

        if (logoPequeno) {
            logoPequeno.classList.remove(
                'opacity-100',
                'scale-100'
            );

            logoPequeno.classList.add(
                'opacity-0',
                'scale-75'
            );
        }

        colapsado = false;

        if (guardar) {
            localStorage.setItem(
                'sidebarColapsado',
                'false'
            );
        }
    }

    if (toggleCollapse) {
        toggleCollapse.addEventListener(
            'click',
            () => {

                if (window.innerWidth < 768) {
                    return;
                }

                if (colapsado) {
                    expandirSidebar(true);
                } else {
                    colapsarSidebar(true);
                }

            }
        );
    }

    function inicializarSidebar() {
        aside.classList.add(
            'sidebar-no-transition'
        );

        if (window.innerWidth < 768) {
            aside.classList.remove(
                'w-20'
            );

            aside.classList.add(
                'w-64'
            );

            if (sidebarAbierto) {
                abrirSidebar(false);
            } else {
                cerrarSidebar(false);
            }
        } else {
            aside.classList.remove(
                '-translate-x-full'
            );

            if (overlay) {
                overlay.classList.add(
                    'hidden'
                );
            }

            document.body.classList.remove(
                'overflow-hidden'
            );

            if (colapsado) {
                colapsarSidebar(false);
            } else {
                expandirSidebar(false);
            }
        }

        requestAnimationFrame(() => {
            aside.classList.remove(
                'sidebar-no-transition'
            );

        });
    }

    window.addEventListener(
        'resize',
        inicializarSidebar
    );

    inicializarSidebar();
})();
</script>