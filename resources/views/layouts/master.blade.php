<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title', 'Mi Web')</title>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col font-sans text-gray-900">

    <header class="bg-teal-600 text-white shadow-lg p-6">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-4xl font-extrabold tracking-tight ">
                Web <span class="text-teal-200">RedSocial</span>
            </h1>
            
            <nav class="space-x-6 font-bold text-2xl">
                <a href="#" class="hover:text-teal-200 transition">Inicio</a>
                <a href="#" class="hover:text-teal-200 transition">Perfil</a>
                <a href="#" class="bg-teal-500 px-4 py-2 rounded-lg hover:bg-teal-400">Postear</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto flex-grow p-6 my-8 bg-white shadow-md rounded-xl">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-gray-400 py-6 text-center text-sm">
        <p>Realizado por <span class="text-white font-semibold">Ariadna Delgado Santana</span></p>
        <p class="mt-1">Proyecto Laravel</p>
    </footer>

</body>

</html>
