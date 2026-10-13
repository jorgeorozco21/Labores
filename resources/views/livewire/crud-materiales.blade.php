<div class="flex h-screen overflow-hidden">
    @include('alertas-normales')
    @include('admin.alertas-carga-masiva')

    <!-- Sidebar -->
    @include('admin.sidebar-admin', ['admin' => $admin ])

    <main class="relative flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-4 md:px-8 py-4 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-4">
                <button id="abrir-sidebar" class="md:hidden p-2 text-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div>
                    <h2 class="text-lg md:text-xl font-extrabold text-gray-800 leading-tight">Materiales</h1>
                    <p class="hidden sm:block text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                        Administración de Materiales
                    </p>
                </div>
            </div>

            <!-- Boton con Opciones (Nuevo Material y Carga Masiva) -->
            <div class="relative flex gap-2 text-left" id="dropdown-container">
                @php
                    $opciones = [
                        ['nombreFuncion'  => 'crear', 'texto' => 'Nuevo Material', 'path' => 'M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z'],
                        ['nombreFuncion' => 'abrirCargaMasiva', 'texto' => 'Carga Masiva', 'path' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z']
                    ];
                @endphp

                @include('admin.menu-desplegable', ['opciones' => $opciones])

                @include('admin.boton-exportar-excel', ['nombreFuncion' => "exportarMateriales", 'title' => "Exportar Materiales"])

                @if ($vistaCompleta)
                    @include('admin.boton-eliminar')
                @endif
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6 no-scrollbar space-y-6">
            <!-- Filtros -->
            @include('admin.filtro-materiales')

            <!-- Tabla -->
            @include('admin.tabla-materiales', ['materiales' => $materiales, 'modalDescripcion' => $modalDescripcion, 'vistaCompleta' => $vistaCompleta])
        </div>

        @include('admin.opciones-borrado')
    </main>

    <!-- Modal Crear Material  -->
    <x-admin.modal-nuevo titulo="Materiales" nombreFuncion="guardar" modalFormulario="{{ $modalFormulario }}" esEditar="{{ $esEditar }}">
        @include('Admin.Materiales.form')
    </x-admin.modal-nuevo>

    <!-- Modal de Carga Masiva -->
    <x-admin.modal-carga-masiva subtitulo="Importar Materiales" nombreFuncion="ejecutarCarga" nombreFuncionCerrar="cerrarCargaMasiva"  modalCargaMasiva="{{ $modalCargaMasiva }}" />
</div>
