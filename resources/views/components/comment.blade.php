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

        {{-- Acciones Rápidas: Like de Comentario --}}
        <div class="flex items-center gap-2">
            {{-- Nota: Necesitarás crear esta ruta en web.php --}}
            <form action="{{ route('comments.like', $comentario->id) }}" method="POST">
                @csrf
                <button type="submit" class="text-[10px] text-pink-600 hover:text-pink-700 font-bold flex items-center gap-1 bg-pink-50 px-2 py-1 rounded transition-colors">
                    ❤️ {{ $comentario->likes_count ?? $comentario->likes()->count() }}
                </button>
            </form>
        </div>
    </div>

    {{-- Contenido del Comentario --}}
    <div class="text-gray-700 text-sm leading-relaxed mb-3">
        "{{ $comentario->reply }}" {{-- Campo definido en la migración --}}
    </div>

    {{-- Acciones de Gestión (Editar/Borrar) --}}
    @auth
        @if(auth()->id() === $comentario->user_id || auth()->user()->can('edit comment') || auth()->user()->can('delete comment'))
            <div class="flex gap-2 justify-end pt-2 border-t border-gray-200/50">
                
                @if(auth()->id() === $comentario->user_id || auth()->user()->can('edit comment'))
                    <x-button :href="route('comments.edit', $comentario->id)" variant="outline" size="sm" class="text-[10px] h-7 px-2 border-gray-200 text-gray-500 hover:bg-gray-100">
                        Editar
                    </x-button>
                @endif

                @if(auth()->id() === $comentario->user_id || auth()->user()->can('delete comment'))
                    <form action="{{ route('comments.destroy', $comentario->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <x-button type="submit" variant="danger" size="sm" class="text-[10px] h-7 px-2" onclick="return confirm('¿Borrar comentario?')">
                            Borrar
                        </x-button>
                    </form>
                @endif
            </div>
        @endif
    @endauth
</div>