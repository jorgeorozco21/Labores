<div wire:poll.10s class="flex h-screen overflow-hidden">
    @include('alertas-normales')
    <!-- Sidebar -->
    @include('admin.sidebar-admin', ['admin' => $admin])
    <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-4 md:px-8 py-4 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-4">
                <button id="abrir-sidebar" class="md:hidden p-2 rounded-xl bg-gray-50 text-[#7B1FA3] hover:bg-purple-50 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div>
                    <h2 class="text-lg md:text-xl font-extrabold text-gray-800 leading-tight">Informes</h1>
                    <p class="hidden sm:block text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                        Administración de Informes - {{ $laboratorio->nombre }}
                    </p>
                </div>
            </div>

            <div class="flex relative gap-2 text-left">
                @if ($laboratorio->tipo == 'mixto')
                    <a href="{{ url('/admin/informes-inventario/laboratorios/'.$laboratorio->id.'-laboratorio-normal') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium text-white bg-[#7B1FA3] hover:bg-[#6A1B8E] active:scale-95 transition-colors">Inventarios</a>
                    <a href="{{ url('/admin/informes/laboratorios/'.$laboratorio->id.'-laboratorio-normal') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium text-white bg-[#7B1FA3] hover:bg-[#6A1B8E] active:scale-95 transition-colors">Materiales</a>
                    <a href="{{ url('/admin/informes-reportes/laboratorios/'.$laboratorio->id.'-laboratorio-normal') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium text-white bg-[#7B1FA3] hover:bg-[#6A1B8E] active:scale-95 transition-colors">Reportes</a>
                @endif
                @include('admin.boton-modal-plantilla', ['modalPlantilla' => $modalPlantilla])
                @include('admin.boton-nueva-computadora')
                @include('admin.boton-exportar-excel', ['nombreFuncion' => 'exportarComputadoras', 'title' => 'Exportar Informes de Computadoras'])
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6 no-scrollbar space-y-6">
            @include('components.admin.filtro-computadoras')
            @include('components.admin.tabla-computadoras', ['computadoras' => $computadoras, 'modalReportes' => $modalReportes, 'modalAuditorias' => $modalAuditorias])
        </div>
    </main>
</div>