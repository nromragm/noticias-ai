<header class="bg-white dark:bg-gray-800 shadow-md dark:shadow-lg transition-colors">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('noticias.index') }}" class="text-2xl font-bold text-blue-600 dark:text-blue-300 hover:text-blue-800">
            NoticIA
        </a>

        <div class="flex items-center gap-4">
            <!-- Botón modo oscuro -->
            <button id="toggle-dark" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 transition text-xl">
                🌙
            </button>

            <!-- Botones de sesión -->
            @auth
                <!-- Usuario autenticado -->
                <a href="{{ route('login') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Mi perfil
                </a>
            @else
                <!-- Invitado -->
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Iniciar sesión
                </a>
            @endauth
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('toggle-dark');
        const html = document.documentElement;

        // Cargar preferencia
        if (localStorage.getItem('theme') === 'dark') {
            html.classList.add('dark');
        }

        toggle?.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            toggle.innerText = html.classList.contains('dark') ? '☀️' : '🌙';
        });

        // Actualizar ícono al cargar
        toggle.innerText = html.classList.contains('dark') ? '☀️' : '🌙';
    });
</script>