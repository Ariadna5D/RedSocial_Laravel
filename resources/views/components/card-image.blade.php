{{-- resources/views/components/card.blade.php --}}
@props(['title' => null, 'footer' => null, 'image' => null])

<div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100 flex flex-col h-full transition-transform hover:scale-[1.01]">
    {{-- Imagen de cabecera opcional --}}
    @if($image)
        <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-48 object-cover">
    @endif

    <div class="p-6 flex-grow">
        {{-- Título de la Card --}}
        @if($title)
            <h3 class="text-xl font-bold text-gray-800 mb-3 border-b border-teal-100 pb-2">
                {{ $title }}
            </h3>
        @endif

        {{-- Contenido principal (Slot) --}}
        <div class="text-gray-600 text-sm leading-relaxed">
            {{ $slot }}
        </div>
    </div>

    {{-- Footer opcional --}}
    @if($footer)
        <div class="bg-gray-50 p-4 border-t border-gray-100">
            {{ $footer }}
        </div>
    @endif
</div>