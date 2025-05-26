<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Noticias')</title>

    @vite('resources/css/app.css') {{-- Asegúrate de tener Vite configurado --}}
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white transition-colors duration-300">

    {{-- Header --}}
    @include('components.header')

    {{-- Contenido principal --}}
    <main class="container mx-auto px-4 py-8">
        {{ $slot }}
    </main>

    {{-- Footer opcional --}}
    <footer class="text-center text-sm text-gray-500 py-4 dark:text-gray-400">
        &copy; {{ now()->year }} Mi Noticias. Todos los derechos reservados.
    </footer>

    @livewireScripts
</body>
</html>
