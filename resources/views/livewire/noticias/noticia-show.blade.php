<div class="container mx-auto px-2 sm:px-4 py-6 sm:py-10 max-w-6xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="p-3 sm:p-6">
            <h1 class="text-2xl sm:text-4xl font-extrabold mb-4 text-blue-700 dark:text-blue-300 leading-tight text-center break-words">
                {{ $noticia->titulo }}
            </h1>

            @if($noticia->urlImg)
                <div class="w-full flex justify-center mb-4 sm:mb-6">
                    <img src="{{ $noticia->urlImg }}" alt="Imagen"
                        class="rounded-lg shadow-md max-h-60 sm:max-h-96 object-contain border border-gray-200 dark:border-gray-700 w-full max-w-xl">
                </div>
            @endif

            {{-- Video opcional --}}
            @if($noticia->urlVideo)
                <div class="w-full flex justify-center mb-4 sm:mb-6">
                    <iframe src="{{ $noticia->urlVideo }}" frameborder="0" allowfullscreen
                        class="rounded-lg shadow-md w-full max-w-xl h-60 sm:h-96"></iframe>
                </div>
            @endif

            <p class="text-base sm:text-lg text-gray-700 dark:text-gray-200 mb-4 sm:mb-6 italic text-center break-words">
                {{ $noticia->descripcion }}
            </p>

            <div class="prose max-w-none mb-6 sm:mb-8 dark:prose-invert break-words">
                {!! $noticia->contenido !!}
            </div>

            {{-- Valorar noticia --}}
            <div class="my-6 sm:my-8">
                <livewire:valorar-noticia :noticia="$noticia" />
            </div>

            {{-- Preguntar IA --}}
            <div class="my-6 sm:my-8">
                <livewire:preguntar-i-a :noticia="$noticia" />
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 sm:pt-6 mt-6 sm:mt-8">
                <h2 class="text-lg sm:text-2xl font-semibold mb-4 text-blue-600 dark:text-blue-300 flex items-center gap-2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-400 dark:text-blue-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V10a2 2 0 012-2h2m10-4h-4m0 0V4m0 0v4"></path></svg>
                    Comentarios
                </h2>

                @auth
                    <livewire:comentarios.crear-comentario :noticia="$noticia" />
                @else
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-gray-900 rounded text-blue-700 dark:text-blue-200">
                        <a href="{{ route('login') }}" class="underline hover:text-blue-900 dark:hover:text-white transition">
                            Inicia sesión para comentar.
                        </a>
                    </div>
                @endauth

                <livewire:comentarios.seccion-comentarios :noticia="$noticia" />

                <hr class="my-6 sm:my-8 border-gray-200 dark:border-gray-700">

                {{-- Noticias recomendadas --}}
                <div class="mt-6 sm:mt-8">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Noticias recomendadas</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        @foreach($relacionadas as $relacionada)
                            <a href="{{ route('noticias.show', $relacionada->id) }}"
                               class="block p-3 sm:p-4 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition">
                                @if($relacionada->urlImg)
                                    <img src="{{ $relacionada->urlImg }}" alt="Imagen relacionada"
                                         class="w-full h-28 sm:h-32 object-cover rounded mb-2 sm:mb-3 border border-gray-200 dark:border-gray-700">
                                @endif
                                <h4 class="text-sm sm:text-base font-semibold text-blue-600 dark:text-blue-400 mb-1 break-words">{{ $relacionada->titulo }}</h4>
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 break-words">{{ Str::limit($relacionada->descripcion, 100) }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>