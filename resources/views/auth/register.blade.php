@extends('layouts.master')

@section('title', 'Crear Cuenta')

@section('content')
<div class="max-w-md mx-auto bg-white p-8">
    <h2 class="text-2xl font-bold text-teal-600 mb-6 text-center">Crea tu cuenta</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name" class="block font-medium text-sm text-gray-700">Nombre completo</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="email" class="block font-medium text-sm text-gray-700">Correo Electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password" class="block font-medium text-sm text-gray-700">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirmar Contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col gap-4 mt-6">
            <button type="submit" class="w-full bg-teal-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-teal-700 transition">
                Registrarse
            </button>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    ¿Ya tienes una cuenta? 
                    <a href="{{ route('login') }}" class="font-bold text-teal-600 hover:underline">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>
        </div>
    </form>
</div>
@endsection