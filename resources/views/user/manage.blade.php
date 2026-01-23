@extends('layouts.master')

@section('title', 'Gestión de Usuarios')

@section('content')
    <div class="p-6">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">
            Panel de Administración de Usuarios
        </h2>

        <x-tablaCRUD :datos="$usuarios" rutaVer="user.profile" rutaBorrar="user.destroy" />
    </div>
@endsection