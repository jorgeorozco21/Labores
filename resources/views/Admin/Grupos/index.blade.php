<!DOCTYPE html>
<html lang="en" class="h-full">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Grupos</title>
        @livewireStyles
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="icon" type="image/webp" href="{{ asset('images/logos/labores_icono_morado.webp') }}">
        <style>
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        </style>
    </head>
    <body class="h-full bg-[#F7F6F8]">

        @livewire('crud-grupos')

        @livewireScripts
    </body>
</html>