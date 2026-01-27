@extends('layouts.master')

@section('title', 'Editar Perfil')

@section('content')
    <div class="p-6">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">
            Editar Comentario: {{ $comment->id }}
        </h2>
        <x-dynamic-form 
        :modelo="$comment" 
        :accion="route('comments.update', $comment)" 
        metodo="PATCH" 
        submitText="Actualizar Comentario" />
    </div>
@endsection
