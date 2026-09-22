@props(['id' => 'modal-edit','titulo'])
<div id="{{ $id }}" style="display: none;" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[30px] shadow-2xl w-full max-w-lg relative overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-extrabold text-gray-800">{{ $titulo }}</h3>
            <button id="cerrar-modal-edit" type="button" class="text-gray-400 hover:text-red-500 font-bold text-xl transition-colors">✕</button>
        </div>
        <!-- Formulario Editar Usuario -->
        <div class="p-8 pt-0 max-h-[80vh] overflow-y-auto">
            <form method="post" id="formulario-editar" class="space-y-4">
                @csrf
                {{ method_field('PATCH') }}
                {{ $slot }}
            </form>
        </div>
    </div>
</div>