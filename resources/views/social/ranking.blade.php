@extends('layouts.master')

@section('title', 'Gestión de Usuarios')

@section('content')
    <div class="p-6">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">
            Ranking
        </h2>
        <x-tabla :datos="$usuarios" :configuracion="$configuracion" />
    </div>
@endsection