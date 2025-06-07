<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title>NoticIA</title>
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
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <!--'resources/sass/app.scss', -->
        @vite(['resources/js/app.js', 'resources/js/darkmode-boton.js', 'resources/js/darkmode-auto.js', 'resources/css/app.css'])
        @livewireStyles
    </head>
    <body class="min-h-screen flex flex-col bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white transition-colors duration-300">
        <!-- Page Heading -->
        <livewire:layout.navigation />

        <!-- Page Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="text-center text-sm text-gray-500 py-4 dark:text-gray-400">
            &copy; {{ now()->year }} NoticIA. Todos los derechos reservados. <a href="/aboutNoticIA">Sobre NoticIA.</a>
        </footer>

        @livewireScripts
    </body>
</html>
