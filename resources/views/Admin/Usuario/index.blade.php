<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/webp" href="{{ asset('images/logos/labores_icono_morado.webp') }}">
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        /* Scroll personalizado */
        .scroll-rojo::-webkit-scrollbar { width: 8px; }
        .scroll-rojo::-webkit-scrollbar-track { background: #fee2e2; border-radius: 10px;}
        .scroll-rojo::-webkit-scrollbar-thumb { background: #dc2626; border-radius: 10px;}
        .scroll-rojo::-webkit-scrollbar-thumb:hover { background: #b91c1c; }
    </style>
</head>
<body class="h-full bg-[#F7F6F8]">
    <!-- Alertas -->
    <x-admin.alertas-usuarios />
    <x-admin.alertas-carga-masiva />
    
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-admin.sidebar-admin :admin="$admin" />

        <main class="relative flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="bg-white border-b border-gray-100 px-4 md:px-8 py-4 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-4">
                    <button id="abrir-sidebar" class="md:hidden text-gray-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div>
                        <h2 class="text-lg md:text-xl font-extrabold text-gray-800 leading-tight">Usuarios</h1>
                        <p class="hidden sm:block text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                            Administración de Usuarios
                        </p>
                    </div>
                </div>

                <!-- Boton con Opciones-->
                <div class="relative flex gap-2 text-left" id="dropdown-container">
                    <x-admin.boton-agregar id="btn-dropdown" />

                    <x-admin.boton-exportar-excel route="admin.usuarios.exportarUsuarios" title="Exportar Usuarios" />

                    <x-admin.boton-eliminar id="borrar-algunos" />

                    <x-admin.menu-desplegable id="dropdown-menu">
                        <x-admin.elemento-menu-desplegable id="abrir-modal" texto="Nuevo Usuario">
                            <svg class="w-4 h-4 text-[#6A1B8E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </x-admin.elemento-menu-desplegable>

                        <x-admin.divisor-menu-desplegable />

                        <x-admin.elemento-menu-desplegable id="abrir-carga-masiva" texto="Carga Masiva">
                            <svg class="w-4 h-4 text-[#6A1B8E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </x-admin.elemento-menu-desplegable>

                        <x-admin.divisor-menu-desplegable />

                        <x-admin.elemento-menu-desplegable id="edicion-masiva" texto="Edición Masiva">
                            <svg class="w-4 h-4 text-[#6A1B8E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14a7 7 0 00-7 7h5" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 21l6.5-6.5a1.5 1.5 0 012.121 2.121L15.621 23H13.5v-2z" />
                            </svg>
                        </x-admin.elemento-menu-desplegable>
                    </x-admin.menu-desplegable>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-6 no-scrollbar space-y-6">
                <!-- Filtros -->
                <x-admin.filtro-usuarios :grupos="$grupos" />

                <!-- Tabla de Usuarios -->
                <x-admin.tabla-usuarios :usuarios="$usuarios" :bloqueo="$bloqueo" />
            </div>

            <x-admin.opciones-borrado id="opciones-borrado" />
            
        </main>
    </div>

    <!-- Modal edicion masiva -->
    <div id="modal-edicion-masiva" style="display: none;" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-[30px] shadow-2xl w-full max-w-lg relative overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-extrabold text-gray-800">Cambiar Grupo</h3>
                <button id="cerrar-modal-edicion-masiva" class="text-gray-400 hover:text-red-500 font-bold text-xl transition-colors">✕</button>
            </div>
            <!-- Form de Agregar Usuario -->
            <div class="p-8 pt-0 max-h-[80vh] overflow-y-auto">
                <div class="space-y-4">
                    <div>
                        <label for="grupo-actual" id="label-grupo" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Grupo Actual</label>
                        <select id="grupo-actual" class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#6A1B8E] transition-all">
                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo->id }}">{{ $grupo->grado }}°{{ $grupo->grupo }} - {{ $grupo->nombre }} - {{ $grupo->turno }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="grupo-nuevo" id="label-grupo" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Grupo Nuevo</label>
                        <select id="grupo-nuevo" class="w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:border-[#6A1B8E] transition-all">
                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo->id }}">{{ $grupo->grado }}°{{ $grupo->grupo }} - {{ $grupo->nombre }} - {{ $grupo->turno }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pt-2">
                        <button id="boton-edicion-masiva" class="w-full bg-[#6A1B8E] text-white font-bold py-3 rounded-2xl hover:bg-[#6A1B8E] transition-all shadow-lg shadow-purple-100 active:scale-[0.98]">
                            Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Agregar Usuario -->
    <x-admin.modal-nuevo titulo="Registrar Usuario" :action="route('admin.usuarios.store')">
        @include('Admin.Usuario.form')
    </x-admin.modal-nuevo>
    
    <!-- Modal de Editar Usuario -->
    <x-admin.modal-editar titulo="Editar Usuario">
        @include('Admin.Usuario.form_editar')
    </x-admin.modal-editar>

    <!-- Modal de Carga Masiva -->
    <x-admin.modal-carga-masiva subtitulo="Importar Usuarios" action="/carga-usuario">
        @include('Admin.Usuario.carga_masiva_usuarios')
    </x-admin.modal-carga-masiva>

    <input type="hidden" id="nombre-tabla" value="usuarios">
    <input type="hidden" id="limite-bloqueo" value="{{ $bloqueo->limite_bloqueo }}">

    @vite(['resources/js/Admin/crud_usuarios.js', 'resources/js/Admin/buscador_usuarios.js', 'resources/js/Admin/alertas.js', 'resources/js/Admin/modal.js', 'resources/js/Admin/boton_modales.js', 'resources/js/Admin/borrado_masivo.js'])
</body>
</html>