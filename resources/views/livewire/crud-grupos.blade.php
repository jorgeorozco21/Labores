<div class="flex h-screen overflow-hidden">
    @include('alertas-normales')

    <!-- Sidebar -->
    @include('admin.sidebar-admin', ['admin' => $admin ])

    <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-4 md:px-8 py-4 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-4">
                <button id="abrir-sidebar" class="md:hidden text-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div>
                    <h2 class="text-lg md:text-xl font-extrabold text-gray-800 leading-tight">Grupos</h1>
                    <p class="hidden sm:block text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                        Administración de Grupos
                    </p>
                </div>
            </div>

            <!-- Boton con Opciones (Nuevo Grupo) -->
            <div class="relative flex gap-2 text-left" id="dropdown-container">
                @php
                    $opciones = [
                        ['nombreFuncion' => 'crear', 'texto' => 'Nuevo Grupo', 'path' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z']
                    ];
                @endphp

                @include('admin.menu-desplegable', ['opciones' => $opciones])

                @include('admin.boton-exportar-excel', ['nombreFuncion' => "exportarGrupos", 'title' => "Exportar Grupos"])

                @include('admin.boton-eliminar')
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6 no-scrollbar space-y-6">
            <!-- Filtros -->
            @include('admin.filtro-grupos')

            <!-- Tabla Grupos -->
            @include('admin.tabla-grupos', ['grupos' => $grupos, 'modalLaboratorios' => $modalLaboratorios])
        </div>

        @include('admin.opciones-borrado')
    </main>

    <!-- Modal Crear o Editar Grupo  -->
    <x-admin.modal-nuevo titulo="Grupo" nombreFuncion="guardar" modalFormulario="{{ $modalFormulario }}" esEditar="{{ $esEditar }}">
        @include('Admin.Grupos.form', ['esEditar' => $esEditar])
    </x-admin.modal-nuevo>
    
</div>