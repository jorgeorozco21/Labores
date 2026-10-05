@props(['opciones'])

<div class="relative inline-block text-left" x-data="{ abierto: false }" @click.stop>

    @include('admin.boton-agregar')

    <div x-show="abierto" @click.outside="abierto = false" style="display: none;" class="absolute -right-20 mt-2 w-56 origin-top-right bg-white border border-gray-100 rounded-2xl shadow-2xl scale-95 transition-all duration-200 z-50">
        <div class="py-2">
            @php
                $cantidadOpciones = count($opciones);
            @endphp
            
            @foreach ($opciones as $index => $opcion)
                <x-admin.elemento-menu-desplegable nombreFuncion="{{ $opcion['nombreFuncion'] }}" texto="{{ $opcion['texto'] }}">
                    <svg class="w-4 h-4 text-[#7B1FA3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $opcion['path'] }}" />
                    </svg>
                </x-admin.elemento-menu-desplegable>

                @if ($index < $cantidadOpciones - 1)
                    <x-admin.divisor-menu-desplegable />
                @endif
            @endforeach
        </div>
    </div>
</div>