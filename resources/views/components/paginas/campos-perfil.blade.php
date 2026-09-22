@props(['datos'])

<div class="max-w-6xl mx-auto bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    
    <!-- Encabezado con Avatar/Badge -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-6 mb-8 border-b border-gray-100 gap-4">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-800 tracking-tight">Perfil de Usuario</h1>
                <p class="text-sm text-gray-500 font-medium">Gestiona tu información personal y seguridad</p>
            </div>
        </div>
    </div>

    @if (session('tipo') != "labores")
        <!-- Sección 1: Datos Personales -->
        <div class="mb-6">
            <h2 class="text-sm font-bold text-[#7B1FA3] uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Información de la Cuenta
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Nombre Completo -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-gray-600">Nombre Completo</label>
                    <div class="relative">
                        <input type="text" value="{{ $datos['usuario']->nombre }}" disabled
                            class="w-full bg-gray-50/80 px-4 py-2.5 rounded-xl text-sm font-bold text-gray-700 border border-gray-200 cursor-not-allowed select-none">
                    </div>
                </div>

                <!-- Nombre de Usuario -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-gray-600">Nombre de Usuario</label>
                    <div class="relative">
                        <input type="text" value="{{ $datos['usuario']->nombre_usuario }}" disabled
                            class="w-full bg-gray-50/80 px-4 py-2.5 rounded-xl text-sm font-bold text-gray-700 border border-gray-200 cursor-not-allowed select-none">
                    </div>
                </div>

                <!-- Correo Electrónico -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-gray-600">Correo Electrónico</label>
                    <div class="relative">
                        <input type="email" value="{{ $datos['usuario']->email }}" disabled
                            class="w-full bg-gray-50/80 px-4 py-2.5 rounded-xl text-sm font-bold text-gray-700 border border-gray-200 cursor-not-allowed select-none">
                    </div>
                </div>

                <!-- Escuela -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-gray-600">Escuela / Institución</label>
                    <div class="relative">
                        <input type="text" value="{{ $datos['usuario']->nombreInstitucion }}" disabled
                            class="w-full bg-gray-50/80 px-4 py-2.5 rounded-xl text-sm font-bold text-gray-700 border border-gray-200 cursor-not-allowed select-none">
                    </div>
                </div>

                @if (isset($datos['grupo']) && $datos['grupo'])
                    <!-- Grado/Grupo -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-bold text-gray-600">Grado / Grupo</label>
                        <input type="text" value="{{ $datos['grupo']->grado }} {{ $datos['grupo']->grupo }}" disabled
                            class="w-full bg-gray-50/80 px-4 py-2.5 rounded-xl text-sm font-bold text-gray-700 border border-gray-200 cursor-not-allowed select-none">
                    </div>  

                    <!-- Especialidad -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-bold text-gray-600">Especialidad / Turno</label>
                        <input type="text" value="{{ $datos['grupo']->nombre }} - {{ $datos['grupo']->turno }}" disabled
                            class="w-full bg-gray-50/80 px-4 py-2.5 rounded-xl text-sm font-bold text-gray-700 border border-gray-200 cursor-not-allowed select-none">
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Sección 2: Formulario Cambio de Contraseña -->
    <form method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cambiar la contraseña?')" action="{{ url('/perfil/cambiar-contrasena') }}" 
        class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100/80">
        @csrf
        
        <h2 class="text-sm font-bold text-[#7B1FA3] uppercase tracking-wider mb-4 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Seguridad y Contraseña
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
            <!-- Contraseña Actual -->
            <div class="space-y-1.5">
                <label class="block text-sm font-bold text-gray-700">Contraseña Actual</label>
                <input type="password" name="contrasenaActual" required placeholder="••••••••"
                    class="w-full bg-white px-4 py-2.5 rounded-xl text-sm font-medium text-gray-800 border border-gray-200 focus:border-[#7B1FA3] focus:ring-2 focus:ring-purple-100 focus:outline-none transition-all shadow-xs">
            </div>

            <!-- Nueva Contraseña -->
            <div class="space-y-1.5">
                <label class="block text-sm font-bold text-gray-700">Nueva Contraseña</label>
                <input type="password" name="nuevaContrasena" required placeholder="••••••••"
                    class="w-full bg-white px-4 py-2.5 rounded-xl text-sm font-medium text-gray-800 border border-gray-200 focus:border-[#7B1FA3] focus:ring-2 focus:ring-purple-100 focus:outline-none transition-all shadow-xs">
            </div>

            <!-- Repetir Nueva Contraseña -->
            <div class="space-y-1.5">
                <label class="block text-sm font-bold text-gray-700">Confirmar Contraseña</label>
                <input type="password" name="validarContrasena" required placeholder="••••••••"
                    class="w-full bg-white px-4 py-2.5 rounded-xl text-sm font-medium text-gray-800 border border-gray-200 focus:border-[#7B1FA3] focus:ring-2 focus:ring-purple-100 focus:outline-none transition-all shadow-xs">
            </div>
        </div>

        <div class="flex justify-center">
            <button type="submit" class="inline-flex items-center gap-2 bg-[#7B1FA3] hover:bg-[#6A1B8E] text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-sm active:scale-95 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Actualizar Contraseña
            </button>
        </div>
    </form>
</div>