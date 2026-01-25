@extends('layouts.master')

@section('title', 'Editar Perfil')

@section('content')
    <div class="p-6">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">
            Configuración de Post: {{ $post->id }}
        </h2>

    </div>
@endsection