<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Labores</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="icon" type="image/webp" href="{{ asset('images/logos/labores_icono_morado.webp') }}">
</head>
<body class="h-full bg-[#F7F6F8]">
    <x-admin.alertas-usuarios />

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-admin-labores.sidebar-labores :admin="$admin" />
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="bg-white border-b border-gray-100 px-4 md:px-8 py-4 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-4">
                    <button id="abrir-sidebar" class="md:hidden text-gray-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div>
                        <h2 class="text-lg md:text-xl font-extrabold text-gray-800 leading-tight">Instituciones</h2>
                    </div>
                </div>

                <div class="relative flex gap-2 text-left">
                    <x-admin-labores.boton-agregar id="abrir-modal" />
                </div>
            </header>

            <div class="p-6 space-y-6">
                <x-admin-labores.filtro-instituciones />

                <x-admin-labores.tabla-instituciones :instituciones="$instituciones" />
            </div>
        </main>
    </div>

    <x-admin.modal-editar titulo="Editar Institucion">
        @include('Admin_Labores.Instituciones.form_editar')
    </x-admin.modal-editar>

    <x-admin-labores.modal-nuevo titulo="Nueva Institucion" action="{{ route('labores.instituciones.store') }}" >
        @include('Admin_Labores.Instituciones.form')
    </x-admin-labores.modal-nuevo>

    @vite(['resources/js/Admin_Labores/modales.js', 'resources/js/Admin_Labores/crud_instituciones.js', 'resources/js/Admin/alertas.js', 'resources/js/Admin_Labores/buscador_instituciones.js'])
</body>
</html>