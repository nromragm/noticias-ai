<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-4">{{ $noticia->titulo }}</h1>

    @if($noticia->urlImg)
        <img src="{{ $noticia->urlImg }}" alt="Imagen" class="w-full h-auto object-contain rounded mb-6">
    @endif

    <p class="text-lg text-gray-700 mb-8">
        {{ $noticia->descripcion }}
    </p>


    <div class="prose lg:prose-xl mb-8">
        {!! $noticia->contenido !!}
    </div>

    <livewire:preguntar-i-a :noticia="$noticia" />

    <h2 class="text-2xl font-semibold mb-4">Comentarios</h2>

    @auth
        <livewire:crear-comentario :noticia="$noticia" />
    @else
        <p class="text-gray-600 mb-6">Inicia sesión para comentar.</p>
    @endauth

    <livewire:seccion-comentarios :noticia="$noticia" />
</div>
