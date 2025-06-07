<div class="container mx-auto px-4 py-10 max-w-6xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <h1 class="text-4xl font-extrabold mb-4 text-blue-700 dark:text-blue-300 leading-tight text-center">
                {{ $noticia->titulo }}
            </h1>

            @if($noticia->urlImg)
                <div class="w-full flex justify-center mb-6">
                    <img src="{{ $noticia->urlImg }}" alt="Imagen"
                        class="rounded-lg shadow-md max-h-96 object-contain border border-gray-200 dark:border-gray-700">
                </div>
            @endif

            <p class="text-lg text-gray-700 dark:text-gray-200 mb-6 italic text-center">
                {{ $noticia->descripcion }}
            </p>

            <div class="prose lg:prose-xl max-w-none mb-8 dark:prose-invert">
                {!! $noticia->contenido !!}
            </div>

            {{-- Valorar noticia --}}
            <div class="my-8">
                <livewire:valorar-noticia :noticia="$noticia" />
            </div>

            {{-- Preguntar IA --}}
            <div class="my-8">
                <livewire:preguntar-i-a :noticia="$noticia" />
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-8">
                <h2 class="text-2xl font-semibold mb-4 text-blue-600 dark:text-blue-300 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-400 dark:text-blue-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V10a2 2 0 012-2h2m10-4h-4m0 0V4m0 0v4"></path></svg>
                    Comentarios
                </h2>

                @auth
                    {{-- Comentarios --}}
                    <livewire:comentarios.crear-comentario :noticia="$noticia" />
                @else
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-gray-900 rounded text-blue-700 dark:text-blue-200">
                        <a href="{{ route('login') }}" class="underline hover:text-blue-900 dark:hover:text-white transition">
                            Inicia sesión para comentar.
                        </a>
                    </div>
                @endauth

                <livewire:comentarios.seccion-comentarios :noticia="$noticia" />

                
            </div>
        </div>
    </div>
</div>