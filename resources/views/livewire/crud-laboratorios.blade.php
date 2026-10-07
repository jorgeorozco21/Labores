<div class="flex h-screen overflow-hidden">
    @include('alertas-normales')
    @include('admin.alertas-carga-masiva')

    <!-- Sidebar -->
    @include('admin.sidebar-admin', ['admin' => $admin ])

    <main class="relative flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-4 md:px-8 py-4 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-4">
                <button id="abrir-sidebar" class="md:hidden text-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div>
                    <h2 class="text-lg md:text-xl font-extrabold text-gray-800 leading-tight">Laboratorios</h1>
                    <p class="hidden sm:block text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                        Administración de Laboratorios
                    </p>
                </div>
            </div>

            <!-- Boton con Opciones (Nuevo Laboratorio y Carga Masiva) -->
            <div class="relative flex gap-2 text-left" id="dropdown-container">

                @php
                    $opciones = [
                        ['nombreFuncion' => 'crear', 'texto' => 'Nuevo Laboratorio', 'path' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1'],
                        ['nombreFuncion' => 'abrirCargaMasiva', 'texto' => 'Carga Masiva', 'path' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z']
                    ]
                @endphp

                @include('admin.menu-desplegable', ['opciones' => $opciones])

                @include('admin.boton-exportar-excel', ['nombreFuncion' => "exportarLaboratorios", 'title' => "Exportar Laboratorios"])

                @include('admin.boton-eliminar')

            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6 no-scrollbar space-y-6">
            <!-- Filtros -->
            @include('admin.filtro-laboratorios')

            <!-- Tabla Laboratorios -->
            @include('admin.tabla-laboratorios', ['laboratorios' => $laboratorios, 'opcionesBorrado' => $opcionesBorrado])
        </div>

        @include('admin.opciones-borrado')

    </main>

    <!-- Modal para crear  -->
    <x-admin.modal-nuevo titulo="Registrar Laboratorio" nombreFuncion="guardar" modalFormulario="{{ $modalFormulario }}" esEditar="{{ $esEditar }}">
        @include('Admin.Laboratorios.form', ['esEditar' => $esEditar])
    </x-admin.modal-nuevo>

    <!-- Modal de Carga Masiva -->
    <x-admin.modal-carga-masiva subtitulo="Importar Laboratorios" nombreFuncion="ejecutarCarga" nombreFuncionCerrar="cerrarCargaMasiva"  modalCargaMasiva="{{ $modalCargaMasiva }}" />
</div>