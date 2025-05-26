<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias</title>
    @vite('resources/sass/app.scss')
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @livewireStyles
</head>
<body>
    @include('components.header')

    <!-- resources/views/inicio.blade.php -->
    <livewire:noticia-show />

    @livewireScripts
</body>
</html>
