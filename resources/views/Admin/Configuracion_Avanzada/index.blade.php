<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/webp" href="{{ asset('images/logos/labores_icono_morado.webp') }}">
</head>
<body class="bg-[#F7F6F8]">
    <x-admin.alertas-usuarios />
    <div class="flex h-screen">
        <!-- Sidebar -->
        @if (session('tipo') == 'admin') <x-admin.sidebar-admin :admin="$admin" /> @else <x-paginas.sidebar-perfil /> @endif
        <!-- Contenedor -->
        <main class="flex-1 flex flex-col overflow-hidden bg-[#F9FAFB]">
            <!-- Header -->
            <header class="bg-white border-b border-gray-100 px-4 md:px-8 py-4 flex justify-between items-center shrink-0 md:hidden">
                <div class="flex items-center gap-4">
                    <button id="abrir-sidebar" class="md:hidden text-gray-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8 no-scrollbar space-y-8">
                <!-- Configuraciones avanzadas -->
                <x-admin.campos-configuracion-avanzada :configuracion="$configuracion" />
            </div>
        </main>
    </div>
</body>
</html>