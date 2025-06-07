<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/login', navigate: true);
    }
}; ?>

<header x-data="{ open: false }" class="bg-white dark:bg-gray-800 shadow-md dark:shadow-lg transition-colors">
    <div class="container mx-auto px-4 py-4 flex flex-wrap items-center justify-between gap-2">
        <!-- Logo -->
        <a href="{{ route('noticias.index') }}" class="text-2xl font-bold text-blue-600 dark:text-blue-300 hover:text-blue-800 flex-shrink-0">
            NoticIA
        </a>

        <div class="flex items-center gap-4 flex-1 justify-end">
            <div class="w-28 sm:w-48 md:w-64 lg:w-full">
                <input
                    type="text"
                    id="search-global"
                    placeholder="Buscar noticias..."
                    class="form-input w-full h-9 sm:h-10 rounded-r-none bg-white text-gray-900 border-gray-300
                        dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700
                        focus:ring-blue-500 focus:border-blue-500 transition text-sm sm:text-base"
                    oninput="window.Livewire.dispatch('searchChanged', { value: event.target.value })"
                    onkeydown="if(event.key==='Enter'){window.Livewire.dispatch('searchChanged', { value: event.target.value })}"
                />
            </div>

            <!-- Botón modo oscuro -->
            <button id="toggle-dark" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 transition text-xl flex items-center justify-center w-8 h-8">
                <!-- Luna minimalista -->
                <svg id="moon-icon" class="h-6 w-6 block dark:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z"/>
                </svg>
                <!-- Sol minimalista -->
                <svg id="sun-icon" class="h-6 w-6 hidden dark:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                </svg>
            </button>

            <!-- Botones de sesión (desktop) -->
            @auth
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white dark:bg-gray-900 hover:text-gray-700 dark:hover:text-blue-300 focus:outline-none transition ease-in-out duration-150">
                                @if(auth()->user()->is_premium)
                                    <!-- Icono estrella premium -->
                                    <svg class="h-4 w-4 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.197-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                    </svg>
                                @endif
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin') }}" wire:navigate class="block px-4 py-2 text-gray-700 dark:text-gray-200 dark:hover:bg-gray-900 rounded transition">
                                    {{ __('Panel de administrador') }}
                                </a>
                            @endif
                            <a href="{{ route('profile') }}" wire:navigate class="block px-4 py-2 text-gray-700 dark:text-gray-200 dark:hover:bg-gray-900 rounded transition">
                                {{ __('Perfil') }}
                            </a>
                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start">
                                <a href="#" class="block px-4 py-2 text-gray-700 dark:text-gray-200 dark:hover:bg-gray-900 rounded transition">
                                    {{ __('Log Out') }}
                                </a>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center justify-center gap-2 p-2 rounded-full text-blue-600 hover:bg-blue-100 dark:hover:bg-gray-900 transition" title="Iniciar sesión" aria-label="Iniciar sesión">
                    <span class="text-sm font-medium">Iniciar sesión</span>
                </a>
            @endauth

            <!-- Hamburger (mobile) -->
            <button @click="open = !open" class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none transition">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Responsive Navigation Menu (mobile) -->
    <div x-show="open" @click.away="open = false" class="sm:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <div class="px-4 py-2 text-gray-800 dark:text-gray-200 font-semibold">
                    {{ auth()->user()->name }}
                </div>
                @if(auth()->user()->isAdmin())
                  <x-dropdown-link :href="route('admin')" wire:navigate class="text-gray-700 dark:text-gray-200 dark:hover:bg-gray-900">
                        {{ __('Panel de administrador') }}
                    </x-dropdown-link>
                @endif
                <x-dropdown-link :href="route('profile')" wire:navigate class="text-gray-700 dark:text-gray-200 dark:hover:bg-gray-900">
                    {{ __('Profile') }}
                </x-dropdown-link>
                <button wire:click="logout" class="w-full text-start">
                    <x-dropdown-link class="text-gray-700 dark:text-gray-200 dark:hover:bg-gray-900">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </button>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-gray-900 rounded transition">
                    Iniciar sesión
                </a>
            @endauth
        </div>
    </div>
</header>