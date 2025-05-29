<div class="container mx-auto px-4 py-8">

    {{-- Selector de orden como botones --}}
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
            wire:click="$set('categoria', '')"
            class="px-4 py-2 rounded transition
                {{ $categoria === '' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}
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
                @if($noticia->urlImg)
                    <img src="{{ $noticia->urlImg }}"
                        alt="{{ $noticia->titulo }}"
                        class="w-full h-48 object-cover flex-shrink-0">
                @endif
                <div class="p-4 flex-1 flex flex-col">
                    <h2 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">{{ $noticia->titulo }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 flex-1 overflow-hidden">
                        {{ Str::limit($noticia->descripcion, 400) }}
                    </p>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-auto">
                        Categoría: {{ ucfirst($noticia->categoria) }}
                    </span>
                </div>
            </div>
        </a>
        @empty
            <p class="col-span-full text-gray-500 dark:text-gray-300 text-center">No hay noticias para esta categoría.</p>
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
</div>