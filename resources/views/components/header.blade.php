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
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')" wire:navigate>
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>
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