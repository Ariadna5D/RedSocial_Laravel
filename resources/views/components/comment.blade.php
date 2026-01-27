@props(['comentario'])

@php
    $nombreAutor = $comentario->user->name ?? 'Anónimo';
    $fecha = $comentario->created_at->diffForHumans();
    $inicial = strtoupper(substr($nombreAutor, 0, 1));
@endphp

<div class="p-4 bg-gray-50 rounded-lg border border-gray-100 mb-4 transition-all hover:border-teal-100">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center">
            <div class="h-8 w-8 rounded-full bg-teal-500 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                {{ $inicial }}
            </div>
            <div class="ml-3">
                <p class="text-xs font-bold text-gray-900 leading-none">{{ $nombreAutor }}</p>
                <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">
                    {{ $fecha }}
                    @if($comentario->updated_at != $comentario->created_at)
                        <span class="ml-1 text-teal-500 font-medium">(Editado por {{ $comentario->edited_by }})</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Slot para botones de acción (Like, Editar, Borrar) --}}
        <div class="flex items-center gap-2">
            {{ $slot }}
        </div>
    </div>

    {{-- Contenido del Comentario --}}
    <div class="text-gray-700 text-sm leading-relaxed">
        {{ $comentario->reply }}
    </div>
</div>