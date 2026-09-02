@props(['configuracion'])

<div class="max-w-6xl mx-auto bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    
    <!-- Encabezado con Avatar/Badge -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-6 mb-8 border-b border-gray-100 gap-4">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-800 tracking-tight">Configuracion Avanzada</h1>
                <p class="text-sm text-gray-500 font-medium">Gestiona las configuraciones avanzadas del sistema</p>
            </div>
        </div>
    </div>

    <form class="space-y-8" method="post" action="{{ url("/admin/configuracion-avanzada/actualizar-".$configuracion->id) }}">
        @csrf
        {{ method_field('PUT') }}

        <div class="space-y-4">
            <h2 class="text-sm font-bold text-[#7B1FA3] uppercase tracking-wider">Usuarios</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label for="bloquear-usuarios" class="block text-sm font-bold text-gray-600">Bloquear Usuarios</label>
                    <input name="limite_bloqueo" type="text" id="bloquear-usuarios" value="{{ ($configuracion->limite_bloqueo == -1) ? 'Sin limite' : $configuracion->limite_bloqueo }}" autocomplete="off" onfocus="convertirANumero(this)" onblur="convertirATexto(this)" class="w-full bg-gray-50/80 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-800 border border-gray-200 focus:bg-white focus:border-[#7B1FA3] focus:ring-2 focus:ring-purple-100 focus:outline-none transition-all">
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <h2 class="text-sm font-bold text-[#7B1FA3] uppercase tracking-wider">Solicitudes</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label for="prestamos" class="block text-sm font-bold text-gray-600">Limite de Solicitudes de Prestamos Simultaneas</label>
                    <input name="limite_solicitudes_prestamos" type="text" id="prestamos" value="{{ ($configuracion->limite_solicitudes_prestamos == -1) ? 'Sin limite' : $configuracion->limite_solicitudes_prestamos }}" autocomplete="off" onfocus="convertirANumero(this)" onblur="convertirATexto(this)" class="w-full bg-gray-50/80 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-800 border border-gray-200 focus:bg-white focus:border-[#7B1FA3] focus:ring-2 focus:ring-purple-100 focus:outline-none transition-all">
                </div>

                <div class="space-y-1.5">
                    <label for="computo" class="block text-sm font-bold text-gray-600">Limite de Solicitudes de Computo Simultaneas</label>
                    <input name="limite_solicitudes_computo" type="text" id="computo" value="{{ ($configuracion->limite_solicitudes_computo == -1) ? 'Sin limite' : $configuracion->limite_solicitudes_computo }}" autocomplete="off" onfocus="convertirANumero(this)" onblur="convertirATexto(this)" class="w-full bg-gray-50/80 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-800 border border-gray-200 focus:bg-white focus:border-[#7B1FA3] focus:ring-2 focus:ring-purple-100 focus:outline-none transition-all">
                </div>
            </div>
        </div>

        <div class="flex justify-center pt-2">
            <button type="submit" onclick="return confirm('¿Estás seguro de que quieres guardar estos cambios?');" class="inline-flex items-center gap-2 bg-[#7B1FA3] hover:bg-[#6A1B8E] text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-sm active:scale-95 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

<script>
    function convertirANumero(input){
        if (input.value === 'Sin limite'){
                input.value = '';
                input.type = 'number';
                input.min = '1';
                input.step = '1';
            }
        }

    function convertirATexto(input){
        if (input.value === '') {
            input.type = 'text';
            input.value = 'Sin limite';
        }
    }
</script>