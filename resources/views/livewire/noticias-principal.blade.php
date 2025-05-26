<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Últimas Noticias</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($noticias as $noticia)
            <a href="{{ route('noticias.show', $noticia->id) }}" class="block transform transition-transform hover:scale-105">
                <div class="bg-white rounded-lg shadow-md overflow-hidden min-h-[400px] flex flex-col">
                    @if($noticia['urlImg'])
                        <img src="{{ $noticia['urlImg'] }}" alt="{{ $noticia['titulo'] }}" class="w-full h-48 object-cover">
                    @endif
                    <div class="p-4 flex-1 flex flex-col">
                        <h2 class="text-lg font-semibold mb-2">{{ $noticia['titulo'] }}</h2>
                        <p class="text-sm text-gray-600 mb-3 flex-grow">
                            {{ Str::limit($noticia['descripcion'], 150) }}
                        </p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
