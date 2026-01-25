@extends('layouts.master')

@section('title', 'Editar Perfil')

@section('content')
    <div class="p-6">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">
            Configuración de Perfil
        </h2>

        <x-dynamicForm 
            :datos="$usuario" 
            :action="route('profile.update')" 
            method="PATCH"
            :exclude="['id', 'email_verified_at', 'created_at', 'updated_at', 'role']" 
        />
    </div>
@endsection