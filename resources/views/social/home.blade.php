@extends('layouts.master')

@section('content')
    <div class="p-6">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">Inicio</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($posts as $post)
<x-post :modelo="$post" :maxChars="120">
    {{-- Botón Like (Slot) --}}
    <form action="{{ route('posts.like', $post->id) }}" method="POST">
        @csrf
        <x-button type="submit" variant="outline" size="sm" class="text-pink-600 border-pink-100 bg-pink-50">
            ❤️ {{ $post->likes_count ?? 0 }}
        </x-button>
    </form>

    <x-button :href="route('posts.show', $post->id)" variant="primary" size="sm">
        Ver más
    </x-button>

@auth
    {{-- BLOQUE EDITAR --}}
    @if(auth()->id() === $post->user_id || auth()->user()->can('edit post'))
        <x-button :href="route('posts.edit', $post->id)" variant="secondary" size="sm">
            Editar
        </x-button>
    @endif

    {{-- BLOQUE ELIMINAR (Corregido con Formulario) --}}
    @if(auth()->id() === $post->user_id || auth()->user()->can('delete post'))
        <form action="{{ route('posts.delete', $post->id) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <x-button type="submit" variant="danger" size="sm" onclick="return confirm('¿Estás seguro?')">
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