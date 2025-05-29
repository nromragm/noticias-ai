<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <title>Noticias</title>
    <script>
        // Aplica el modo oscuro lo antes posible (antes de los CSS)
        if (localStorage.getItem('theme') === 'dark'
            || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!--'resources/sass/app.scss', -->
    @vite(['resources/js/app.js', 'resources/js/darkmode-boton.js', 'resources/css/app.css'])
    @livewireStyles
</head>
<body>
    <livewire:layout.navigation />
    <!-- resources/views/inicio.blade.php -->
    <livewire:filtrar-noticias />
    @livewireScripts
</body>
</html>
