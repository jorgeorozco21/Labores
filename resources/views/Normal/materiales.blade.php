<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Materiales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/webp" href="{{ asset('images/logos/labores_icono_morado.webp') }}">
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#F7F6F8] h-full">
    <!-- Header -->
    <x-normal.navbar :laboratorio="$laboratorio" />
    <!-- Contenedor -->
    <main class="flex flex-1 overflow-hidden">
        <div class="flex-1 p-8 overflow-hidden">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Materiales Disponibles - {{ $laboratorio->nombre }}</h1>
                    <p class="text-gray-500 text-sm">Selecciona los materiales que necesitas</p>
                </div>
                <!-- Buscador -->
                <div class="relative w-80">
                    <input type="text" id="buscador" placeholder="Buscar" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#7B1FA3] outline-none transition" autocomplete="off">
                        <span class="absolute left-3 top-2.5 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M21 21l-4.35-4.35m1.85-5.65a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                </div>
            </div>
            <!--Componente de las Cards de Materiales -->
            <x-normal.cards-material :materiales="$materiales" />
        </div>
        <!-- Componente Solicitud de Materiales -->
        <x-normal.solicitud />
    </main>
</body>

<input type="hidden" id="id-laboratorio" value="{{ $laboratorio->id }}">
<input type="hidden" id="nombreLaboratorio" value="{{ $laboratorio->nombre }}">
<input type="hidden" id="id_usuario" value="{{ $usuario->id }}">
<input type="hidden" id="nombre_usuario" value="{{ $usuario->nombre }}">
<input type="hidden" id="email" value="{{ $usuario->email }}">
<input type="hidden" id="grado" value="{{ $usuario->grado }}">
<input type="hidden" id="grupo" value="{{ $usuario->grupo }}">
<input type="hidden" id="nombreGrupo" value="{{ $usuario->nombreGrupo }}">
<input type="hidden" id="turno" value="{{ $usuario->turno }}">
<input type="hidden" id="cantidad-solicitudes" value="{{ $solicitudes->count() }}">
<input type="hidden" id="limite-solicitudes" value="{{ $configuracion->limite_solicitudes_prestamos }}">

@vite(['resources/js/Normal/buscador_materiales.js','resources/js/Normal/creacion_solicitudes.js'])
</html>