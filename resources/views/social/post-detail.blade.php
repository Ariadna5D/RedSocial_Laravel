@extends('layouts.master')

@section('title', 'Detalle del Post')

@section('content')
    <div class="max-w-4xl mx-auto p-6">

        {{-- 1. DETALLE DEL POST --}}
        <div class="mb-10">
            <x-post :modelo="$post" :maxChars="10000">
                @php
                    // Definimos la variable aquí para que siempre exista
                    $postLikeado = auth()->check() && $post->likes->where('user_id', auth()->id())->isNotEmpty();
                @endphp

                @if (auth()->check())
                    {{-- Usuario Logueado: Puede dar Like --}}
                    <form action="{{ route('posts.like', $post->id) }}" method="POST" class="inline">
                        @csrf
                        <x-button type="submit" variant="pink" size="sm"
                            class="{{ $postLikeado ? 'bg-pink-100 text-pink-700 border-pink-200' : 'text-pink-600 border-pink-100 bg-pink-50' }}">
                            {{ $postLikeado ? '❤︎⁠' : '♡' }} {{ $post->likes_count ?? 0 }}
                        </x-button>
                    </form>
                @else
                    {{-- Visitante: Solo ve el contador --}}
                    <x-button variant="pink" size="sm"
                        class="text-gray-400 border-gray-100 bg-gray-50 cursor-not-allowed"
                        title="Inicia sesión para dar like">
                        ♡ {{ $post->likes_count ?? 0 }}
                    </x-button>
                @endif

                {{-- Botones de Gestión (Solo Auth) --}}
                @auth
                    {{-- Editar/Borrar Post --}}
                    @if (auth()->id() === $post->user_id || auth()->user()->can('edit post'))
                        <x-button :href="route('posts.edit', $post->id)" variant="secondary" size="sm">Editar</x-button>
                    @endif

                    @if (auth()->id() === $post->user_id || auth()->user()->can('delete post'))
                        <form action="{{ route('posts.delete', $post->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <x-button type="submit" variant="danger" size="sm"
                                onclick="return confirm('¿Borrar post?')">Eliminar</x-button>
                        </form>
                    @endif
                @endauth
            </x-post>
        </div>

        {{-- 2. SECCIÓN DE COMENTARIOS --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                Comentarios ({{ $post->comments->count() }})
            </h3>
            @auth
        <div class="mb-8 p-4 bg-teal-50/50 rounded-lg border border-teal-100">
            <x-dynamic-form
                :modelo="new \App\Models\Comment"
                :accion="route('comments.store')"
                submitText="Comentar"
                :excepto="['user_id', 'post_id', 'edited_by']"
                :ocultos="['post_id' => $post->id]"
            />
        </div>
    @endauth
            <div class="space-y-4">
                @forelse($post->comments as $comentario)
                    <x-comment :comentario="$comentario">
                        @php
                            $comLikeado = auth()->check() && $comentario->likes->where('user_id', auth()->id())->isNotEmpty();
                        @endphp

                        @if (auth()->check())
                            <form action="{{ route('comments.like', $comentario->id) }}" method="POST" class="inline">
                                @csrf
                                <x-button type="submit" variant="pink"
                                    class="text-[10px] h-7 px-2 {{ $comLikeado ? 'bg-pink-50 border-pink-200' : '' }}">
                                    {{ $comLikeado ? '❤︎⁠' : '♡'  }} {{ $comentario->likes_count ?? 0 }}
                                </x-button>
                            </form>
                        @else
                            <x-button variant="pink"
                                class="text-[10px] h-7 px-2 text-gray-400 border-gray-100 bg-gray-50 cursor-not-allowed">
                                ♡ {{ $comentario->likes_count ?? 0 }}
                            </x-button>
                        @endif

                        @auth
                            @if (auth()->id() === $comentario->user_id || auth()->user()->can('edit comment'))
                                <x-button :href="route('comments.edit', $comentario->id)" variant="secondary" size="sm"
                                    class="text-[10px] h-7 px-2 border-gray-200 text-gray-500">
                                    Editar
                                </x-button>
                            @endif

                            @if (auth()->id() === $comentario->user_id || auth()->user()->can('delete comment'))
                                <form action="{{ route('comments.destroy', $comentario->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <x-button type="submit" variant="danger" size="sm" class="text-[10px] h-7 px-2"
                                        onclick="return confirm('¿Borrar?')">
                                        Borrar
                                    </x-button>
                                </form>
                            @endif
                        @endauth
                    </x-comment>
                @empty
                    <p class="text-gray-500 italic text-center py-4">No hay comentarios aún.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
