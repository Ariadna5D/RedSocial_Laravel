@extends('layouts.master')

@section('title', 'Editar Perfil')

@section('content')
    <div class="p-6">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">
            Editando Post
        </h2>
        
        <x-dynamic-form 
            :modelo="$post" 
            :accion="route('posts.update', $post->id)" 
            metodo="PATCH" 
            :excepto="['user_id', 'edited_by']"
            submitText="Actualizar Post" 
        />
    </div>
@endsection
