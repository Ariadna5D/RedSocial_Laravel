@extends('layouts.master')

@section('title', 'Inicio - Posts')

@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold text-gray-800">Inicio</h2>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($posts as $post)
                <x-post :modelo="$post" titulo="title" cuerpo="description" :maxChars="120" :footerInfo="'Por: ' . $post->user->name . ' | Publicado ' . $post->created_at->diffForHumans()">
                    {{-- TODO LO QUE PONGAS AQUÍ DENTRO SERÁ EL SLOT --}}

                    {{-- Botón Like --}}
                    <form action="{{ route('posts.like', $post->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="bg-pink-100 text-pink-600 px-3 py-1 rounded text-sm font-bold hover:bg-pink-200 transition-colors">
                            ❤️ {{ $post->likes_count ?? 0 }}
                        </button>
                    </form>

                    <a href="{{ route('posts.show', $post->id) }}"
                        class="bg-blue-500 text-white px-3 py-1 rounded text-sm font-bold hover:bg-blue-600 transition-colors">
                        Ver más
                    </a>

                    @auth
                        @if (auth()->id() === $post->user_id || auth()->user()->hasPermissionTo('edit post'))
                            <a href="{{ route('posts.edit', $post->id) }}"
                                class="bg-teal-500 text-white px-3 py-1 rounded text-sm font-bold hover:bg-teal-600 transition-colors">
                                Editar
                            </a>

                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                onsubmit="return confirm('¿Eliminar post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 text-white px-3 py-1 rounded text-sm font-bold hover:bg-red-600 transition-colors">
                                    Borrar
                                </button>
                            </form>
                        @endif
                    @endauth

                </x-post>
            @endforeach
        </div>

    </div>
@endsection
