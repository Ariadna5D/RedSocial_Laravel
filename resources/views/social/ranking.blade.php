@extends('layouts.master')

@section('title', 'Gestión de Usuarios')

@section('content')
    <div class="p-6">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">
            Ranking de Likes
        </h2>
        <x-tablaRanking :datos="$usuarios" :configuracion="$configuracion" />
    </div>
@endsection