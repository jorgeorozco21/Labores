<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    @include('admin.sidebar-admin', ['admin' => $admin])
    <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-4 md:px-8 py-4 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-4">
                <button id="abrir-sidebar" class="md:hidden text-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div>
                    <h2 class="text-lg md:text-xl font-extrabold text-gray-800 leading-tight">Informes</h1>
                    <p class="hidden sm:block text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                        Informes de Laboratorios
                    </p>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6 no-scrollbar space-y-6">
            @include('admin.filtro-laboratorios')
            @include('admin.card-laboratorio', ['laboratorios' => $laboratorios])
        </div>
    </main>
</div>