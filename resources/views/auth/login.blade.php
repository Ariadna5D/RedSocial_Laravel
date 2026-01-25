@extends('layouts.master')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="max-w-md mx-auto bg-white p-8">
    <h2 class="text-2xl font-bold text-teal-600 mb-6 text-center">Bienvenido de nuevo</h2>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">Correo Electrónico</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                   class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password" class="block font-medium text-sm text-gray-700">Contraseña</label>
            <input id="password" type="password" name="password" required 
                   class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                <span class="ms-2 text-sm text-gray-600">Recordar mi sesión</span>
            </label>
        </div>

        <div class="flex flex-col gap-4 mt-6">
            <button type="submit" class="w-full bg-teal-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-teal-700 transition">
                Entrar
            </button>

            <a href="{{ route('index') }}" class="w-full text-center bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg hover:bg-gray-300 transition">
                Explorar como Invitado
            </a>
        </div>

        <div class="mt-6 text-center space-y-2">
            @if (Route::has('password.request'))
                <a class="block text-sm text-gray-600 hover:text-teal-600 underline" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif

            <p class="text-sm text-gray-600">
                ¿No tienes cuenta? 
                <a href="{{ route('register') }}" class="font-bold text-teal-600 hover:underline">
                    Regístrate aquí
                </a>
            </p>
        </div>
    </form>
</div>
@endsection