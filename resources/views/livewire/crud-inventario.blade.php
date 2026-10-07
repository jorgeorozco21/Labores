<div class="flex h-screen overflow-hidden">
    @include('alertas-normales')
    @include('admin.alertas-carga-masiva')

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
                    <h2 class="text-lg md:text-xl font-extrabold text-gray-800 leading-tight">Inventario</h1>
                    <p class="hidden sm:block text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                        @if($laboratorio)
                            Inventario del Laboratorio: {{ $laboratorio->nombre }}
                        @else
                            Inventario General
                        @endif
                    </p>
                </div>
            </div>

            <!-- Boton con Opciones (Nuevo Material y Carga Masiva) -->
            <div class="relative flex gap-2 text-left" id="dropdown-container">
                @php
                    $opciones = [
                        ['nombreFuncion' => 'crear', 'texto' => 'Nuevo Inventario', 'path' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        ['nombreFuncion' => 'abrirCargaMasiva', 'texto' => 'Carga Masiva', 'path' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z']
                    ]
                @endphp

                @if ($laboratorio)
                    @include('admin.menu-desplegable', ['opciones' => $opciones])
                    @include('admin.boton-exportar-excel', ['nombreFuncion' => "exportarInventarioLaboratorio({$laboratorio->id})", 'title' => "Exportar Inventarios"])
                    @include('admin.boton-eliminar')
                    
                    <a href="{{ url('/admin/informes/laboratorios/'.$laboratorio->id.'-laboratorio-normal') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium text-white bg-[#7B1FA3] hover:bg-[#6A1B8E] active:scale-95 transition-colors">Materiales</a>
                    <a href="{{ url('/admin/informes-reportes/laboratorios/'.$laboratorio->id.'-laboratorio-normal') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium text-white bg-[#7B1FA3] hover:bg-[#6A1B8E] active:scale-95 transition-colors">Reportes</a>
                    @if ($laboratorio->tipo == 'mixto')
                    <a href="{{ url('/admin/informes/laboratorios/'.$laboratorio->id.'-laboratorio-computo/computadoras') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium text-white bg-[#7B1FA3] hover:bg-[#6A1B8E] active:scale-95 transition-colors">Computadoras</a>
                    @endif
                @else
                    @include('admin.boton-exportar-excel', ['nombreFuncion' => "exportarInventario", 'title' => "Exportar Inventarios"])
                @endif
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6 no-scrollbar space-y-6">
            <!-- Filtros -->
            @include('admin.filtro-inventario', ['laboratorios' => $laboratorios])

            <!-- Tabla de Inventarios -->
            @include('admin.tabla-inventario', ['inventarios' => $inventarios])
        </div>

        @include('admin.opciones-borrado')

    </main>

    <!-- Modal Crear Inventario  -->
    <x-admin.modal-nuevo titulo="Registrar Inventario" nombreFuncion="guardar" modalFormulario="{{ $modalFormulario }}" esEditar="{{ $esEditar }}">
        @include('Admin.Inventario.form')
    </x-admin.modal-nuevo>
    
    <!-- Modal de Carga Masiva -->
    <x-admin.modal-carga-masiva subtitulo="Importar Inventarios" nombreFuncion="ejecutarCarga" nombreFuncionCerrar="cerrarCargaMasiva"  modalCargaMasiva="{{ $modalCargaMasiva }}" />
</div>
