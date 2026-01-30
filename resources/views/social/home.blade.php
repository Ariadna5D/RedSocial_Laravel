@extends('layouts.master')

@section('content')
    <div class="p-6">
        @auth
            <div class="mb-10 p-6 bg-white rounded-xl border border-gray-200 shadow-sm">
                <h3 class="text-lg font-bold mb-4 text-teal-700 uppercase tracking-wider">¿Qué estás pensando?</h3>
                <x-dynamic-form
                    :modelo="new \App\Models\Post"
                    :accion="route('posts.store')"
                    submitText="Publicar ahora"
                    :excepto="['user_id', 'edited_by']"
                />
            </div>
        @endauth

        <x-colores/>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($posts as $post)
                <x-post :modelo="$post" :maxChars="120">
                    @php
                        // comrpobamos por cada like, si ya le ha dado like, esto solo si está logueado
                        $yaTieneLike = auth()->check() && $post->likes->where('user_id', auth()->id())->isNotEmpty();
                    @endphp

                    @if (auth()->check())
                        <form action="{{ route('posts.like', $post->id) }}" method="POST" class="inline">
                            @csrf
                            <x-button type="submit" variant="pink" size="sm"
                                class="{{ $yaTieneLike ? 'bg-pink-100 text-pink-700 border-pink-200' : 'text-pink-600 border-pink-100 bg-pink-50' }}">
                                {{ $yaTieneLike ? '❤︎⁠' : '♡' }} {{ $post->likes_count ?? 0 }}
                            </x-button>
                        </form>
                    @else
                        <x-button variant="pink" size="sm"
                            class="text-gray-400 border-gray-100 bg-gray-50"
                            title="Debes iniciar sesión para dar like">
                            ♡ {{ $post->likes_count ?? 0 }}
                        </x-button>
                    @endif

                    <x-button :href="route('posts.show', $post->id)" variant="primary" size="sm">
                        Ver más
                    </x-button>

                    @auth
                        @if (auth()->id() === $post->user_id || auth()->user()->can('edit post'))
                            <x-button :href="route('posts.edit', $post->id)" variant="secondary" size="sm">
                                Editar
                            </x-button>
                        @endif

                        @if (auth()->id() === $post->user_id || auth()->user()->can('delete post'))
                            <form action="{{ route('posts.delete', $post->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" variant="danger" size="sm"
                                    onclick="return confirm('¿Estás seguro?')">
                                    Eliminar
                                </x-button>
                            </form>
                        @endif
                    @endauth

                </x-post>
            @endforeach
        </div>
    </div>
@endsection
