@extends('layouts.master')

@section('title', 'Acceso Denegado')

@section('content')
    <div class="text-center py-12">
        <h1 class="text-6xl font-bold text-red-500 mb-4">Error: 404</h1>
        <h2 class="text-2xl font-semibold text-gray-800">Página no encontrada.</h2>
        <a href="{{ route('index') }}" class="mt-6 inline-block bg-teal-600 text-white px-6 py-2 rounded-lg">
            Volver al Inicio
        </a>
    </div>
@endsection
