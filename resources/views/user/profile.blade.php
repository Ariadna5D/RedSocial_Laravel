@extends('layouts.master')

@section('title', 'Perfil de usuario')

@section('content')
    <div class="p-6">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">
            Panel del usuario {{ $usuario->name }}
        </h2>

    </div>
@endsection