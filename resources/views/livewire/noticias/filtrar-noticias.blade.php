<div class="container mx-auto px-4 py-8">

    {{-- Filtro --}}
    <div class="flex flex-wrap justify-center gap-2 mb-6">
        <button
            wire:click="$set('orden', 'desc')"
            class="px-4 py-2 rounded transition
                {{ $orden === 'desc' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}
                hover:bg-blue-500 hover:text-white dark:hover:bg-blue-500 dark:hover:text-white focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm">
            Más recientes
        </button>
        <button
            wire:click="$set('orden', 'asc')"
            class="px-4 py-2 rounded transition
                {{ $orden === 'asc' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}
                hover:bg-blue-500 hover:text-white dark:hover:bg-blue-500 dark:hover:text-white focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm">
            Más antiguas
        </button>
    </div>

    {{-- Botones de categorías --}}
    <div class="flex flex-wrap justify-center gap-2 mb-6">

        <button
            wire:click="$set('categoria', null)"
            class="px-4 py-2 rounded transition
                {{ $categoria === null ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}
                hover:bg-blue-500 hover:text-white dark:hover:bg-blue-500 dark:hover:text-white focus:ring-2 focus:ring-blue-400 focus:outline-none">
            Todas
        </button>

        @foreach($categorias as $cat)
            <button
                wire:click="$set('categoria', '{{ $cat }}')"
                class="px-4 py-2 rounded transition
                    {{ $categoria === $cat ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}
                    hover:bg-blue-500 hover:text-white dark:hover:bg-blue-500 dark:hover:text-white focus:ring-2 focus:ring-blue-400 focus:outline-none">
                {{ ucfirst($cat) }}
            </button>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($noticias as $noticia)
            <a href="{{ route('noticias.show', $noticia->id) }}"
        class="block transition-shadow transition-colors duration-200 hover:shadow-2xl hover:border-blue-500 dark:hover:border-blue-400">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden flex flex-col min-h-[28rem] border border-gray-100 dark:border-gray-700">
                @if($noticia->img)
                    <img src="{{ asset('storage/' . $noticia->img) }}"
                        alt="{{ $noticia->titulo }}"
                        class="w-full h-48 object-cover flex-shrink-0">
                @elseif($noticia->urlImg)
                    <img src="{{ $noticia->urlImg }}"
                        alt="{{ $noticia->titulo }}"
                        class="w-full h-48 object-cover flex-shrink-0">
                @endif

                <div class="p-4 flex-1 flex flex-col">
                    <h2 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">{{ $noticia->titulo }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 flex-1 overflow-hidden">
                        {{ Str::limit($noticia->contenido, 200) }}
                    </p>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-auto flex items-center gap-2">
                        Categoría: {{ ucfirst($noticia->categoria) }}
                        <span>·</span>
                        {{ $noticia->source ?? 'Desconocida' }}
                        <span>·</span>
                        {{ \Carbon\Carbon::parse($noticia->published_at)->format('d/m/Y H:i') }}
                        <span>·</span>
                        <svg class="inline w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                        </svg>
                        {{ $noticia->comentarios_count ?? $noticia->comentarios()->count() }}
                        {{-- Valoración --}}
                        <span>·</span>
                        <svg class="inline w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <polygon points="10,1 12.59,7.36 19.51,7.64 14,12.26 15.82,19.02 10,15.27 4.18,19.02 6,12.26 0.49,7.64 7.41,7.36"/>
                        </svg>
                        {{ number_format($noticia->valoracionPromedio(), 1, ',', '') ?? '0,0' }}
                    </span>
                </div>
            </div>
        </a>
        @empty
            <p class="col-span-full text-gray-500 dark:text-gray-300 text-center">No hay noticias para esta busqueda.</p>
        @endforelse
    </div>
    {{-- Botón Ver más --}}
    @if($total > count($noticias))
        <div class="flex justify-center mt-6">
            <button wire:click="loadMore"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                Ver más
            </button>
        </div>
    @endif


    {{-- Botón Ir arriba --}}
    <button
        onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
        class="fixed bottom-6 right-6 z-50 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg p-3 transition flex items-center justify-center"
        title="Ir arriba"
        style="display: none;"
        id="scrollToTopBtn"
    >
        <!-- Icono flecha arriba -->
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
        </svg>
    </button>

    <script>
        // Mostrar el botón solo cuando se hace scroll hacia abajo
        window.addEventListener('scroll', function() {
            const btn = document.getElementById('scrollToTopBtn');
            if (window.scrollY > 200) {
                btn.style.display = 'flex';
            } else {
                btn.style.display = 'none';
            }
        });
    </script>
</div>