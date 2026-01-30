@props(['comentario'])

@php
    $nombreAutor = $comentario->user->name ?? 'Anónimo';
    $fecha = $comentario->created_at->diffForHumans();

    $fechaEdicion = $comentario->updated_at ? $comentario->updated_at->diffForHumans() : '';

    $inicial = $comentario->user->pic;
    $color = $comentario->user->theme;

    $fueEditado = !empty($comentario->edited_by) && $comentario->updated_at->gt($comentario->created_at);
@endphp

<div class="p-4 bg-gray-50 rounded-lg border border-gray-100 mb-4 transition-all hover:border-teal-100">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center">
            <div class="h-8 w-8 rounded-full bg-{{ $color }}-300 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                {{ $inicial }}
            </div>
            <div class="ml-3">
                <p class="text-xs font-bold text-gray-900 leading-none">{{ $nombreAutor }}</p>
                <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">
                    {{ $fecha }}

                    @if($fueEditado)
                        <span class="ml-1 text-teal-500 font-medium">
                            • Editado por {{ $comentario->edited_by }} ({{ $fechaEdicion }})
                        </span>
                    @endif
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            {{ $slot }}
        </div>
    </div>
    <div class="text-gray-700 text-sm leading-relaxed">
        {{ $comentario->reply }}
    </div>
</div>
