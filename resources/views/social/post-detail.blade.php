@extends('layouts.master')

@section('title', 'Detalle del Post')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        
        {{-- 1. DETALLE DEL POST --}}
        <div class="mb-10">
            <x-post 
                :modelo="$post" 
                :maxChars="10000"
            >
                {{-- Botones de acción dentro del post --}}
                @auth
                    <div class="flex gap-2">
                        {{-- Botón Like del Post --}}
                        <form action="{{ route('posts.like', $post->id) }}" method="POST">
                            @csrf
                            <x-button type="submit" variant="outline" size="sm" class="text-pink-600 border-pink-100 bg-pink-50">
                                ❤️ {{ $post->likes_count ?? 0 }}
                            </x-button>
                        </form>

                        {{-- Editar Post --}}
                        @if(auth()->id() === $post->user_id || auth()->user()->can('edit post'))
                            <x-button :href="route('posts.edit', $post->id)" variant="secondary" size="sm">
                                Editar
                            </x-button>
                        @endif

                        {{-- Eliminar Post --}}
                        @if(auth()->id() === $post->user_id || auth()->user()->can('delete post'))
                            <form action="{{ route('posts.delete', $post->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" variant="danger" size="sm" onclick="return confirm('¿Estás seguro?')">
                                    Eliminar
                                </x-button>
                            </form>
                        @endif
                    </div>
                @endauth
            </x-post>
        </div>

        {{-- 2. SECCIÓN DE COMENTARIOS --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                Comentarios ({{ $post->comments->count() }})
            </h3>

            {{-- Lista de comentarios usando el componente --}}
            <div class="space-y-4">
                @forelse($post->comments as $comentario)
                    <x-comment :comentario="$comentario" />
                @empty
                    <p class="text-gray-500 italic text-sm text-center py-4">
                        Aún no hay comentarios. ¡Sé el primero en decir algo!
                    </p>
                @endforelse
            </div>
        </div>
    </div>
@endsection