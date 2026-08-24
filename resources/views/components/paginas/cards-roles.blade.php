<div class="flex flex-col md:flex-row justify-center items-stretch gap-8 mb-24 max-w-6xl mx-auto px-4">

    @if (session('normal'))
        <a href="{{ route('activar.rol','normal') }}" class="block group w-full md:w-1/3">
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100 relative overflow-hidden hover:shadow-xl hover:shadow-purple-500/10 transition-all duration-300 h-full min-h-[430px] flex flex-col">
                <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-purple-50 transition-colors">
                    <svg class="w-6 h-6 text-gray-500 group-hover:text-[#7B1FA3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold mb-4 min-h-[56px]">
                    Alumno
                </h2>
                <p class="text-gray-400 text-sm leading-relaxed mb-10">
                    Solicita materiales o computadoras para tus actividades académicas y reporta fallas en los equipos para su pronta revisión.
                </p>
                <div class="w-full py-4 bg-gray-100 text-gray-500 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 group-hover:bg-[#6A1B8E] group-hover:text-white transition-all mt-auto">
                    Acceder
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </div>
            </div>
        </a>
    @endif

    @if (session('encargado'))
        <a href="{{ route('activar.rol','encargado') }}" class="block group w-full md:w-1/3">
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100 relative overflow-hidden hover:shadow-xl hover:shadow-purple-500/10 transition-all duration-300 h-full min-h-[430px] flex flex-col">
                <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-purple-50 transition-colors">
                    <svg class="w-6 h-6 text-gray-500 group-hover:text-[#7B1FA3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold mb-4 min-h-[56px]">
                    Encargado de Área
                </h2>
                <p class="text-gray-400 text-sm leading-relaxed mb-10">
                    Gestiona, aprueba o rechaza solicitudes de materiales y computadoras, además de crear y monitorear reportes e incidencias en tiempo real.
                </p>
                <div class="w-full py-4 bg-gray-100 text-gray-500 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 group-hover:bg-[#6A1B8E] group-hover:text-white transition-all mt-auto">
                    Acceder
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </div>
            </div>
        </a>
    @endif

    @if (session('mantenimiento'))
        <a href="{{ route('activar.rol','mantenimiento') }}" class="block group w-full md:w-1/3">
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100 relative overflow-hidden hover:shadow-xl hover:shadow-purple-500/10 transition-all duration-300 h-full min-h-[430px] flex flex-col">
                <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-purple-50 transition-colors">
                    <svg class="w-6 h-6 text-gray-500 group-hover:text-[#7B1FA3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold mb-4 min-h-[56px]">
                    Encargado de Mantenimiento
                </h2>
                <p class="text-gray-400 text-sm leading-relaxed mb-10">
                    Da seguimiento a los materiales y equipos fuera de servicio para gestionar y ejecutar sus reparaciones.
                </p>
                <div class="w-full py-4 bg-gray-100 text-gray-500 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 group-hover:bg-[#6A1B8E] group-hover:text-white transition-all mt-auto">
                    Acceder
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </div>
            </div>
        </a>
    @endif
</div>