@props(['nombreFuncion','texto'])
<button type="button" @click="abierto = false" wire:click.prevent="{{ $nombreFuncion }}" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-[#6A1B8E] transition-colors group">
    <div class="p-2 bg-purple-50 rounded-lg">
        {{ $slot }}
    </div>
    <div class="text-left">
        <p class="font-bold block">{{ $texto }}</p>
    </div>
</button>