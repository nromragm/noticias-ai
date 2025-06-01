<div class="mt-10 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-2xl mx-auto">
    <h3 class="text-2xl font-bold mb-6 text-blue-700 dark:text-blue-300 flex items-center gap-2 justify-between">
        <span class="flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-500 dark:text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4a8 8 0 018 8 8 8 0 01-8 8 8 8 0 01-8-8 8 8 0 018-8zm0 0v2m0 12v2m8-8h-2M4 12H2m15.07-5.07l-1.42 1.42M6.34 17.66l-1.42 1.42m12.02 0l-1.42-1.42M6.34 6.34L4.92 4.92" />
            </svg>
            Pregúntale a la IA sobre esta noticia
        </span>
        @auth
            @if(auth()->user()->is_premium)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 ml-4">
                    Premium
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 ml-4">
                    Gratis
                </span>
            @endif
        @endauth
    </h3>
    @auth
        @if (!auth()->user()->is_premium)
            <div class="mb-6 flex flex-col items-center justify-center">
                <a href="{{ route('checkout.iniciar') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white hover:text-blue-200 font-semibold py-2 px-6 rounded-lg shadow transition duration-200">
                    🔒 Mejorar a GPT-4.5 por $4.99
                </a>
                <p class="text-sm text-blue-700 dark:text-blue-300 mt-2 text-center">
                    Accede a respuestas más avanzadas de la IA con GPT-4.5
                </p>
            </div>
        @endif
    @endauth

    <form wire:submit.prevent="preguntar" class="flex flex-col gap-2">
        <div class="relative">
            <textarea
                wire:model.defer="pregunta"
                wire:keydown.enter="preguntar"
                class="w-full p-3 pr-12 border rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-700 placeholder-gray-500 dark:placeholder-gray-400 resize-none focus:ring-2 focus:ring-blue-400 transition"
                placeholder="¿Qué quieres saber? (Pulsa Enter para preguntar)"
            ></textarea>
        </div>
    </form>

    @if($respuesta)
        <div class="mt-6 p-4 bg-blue-50 dark:bg-gray-900 rounded-lg shadow flex flex-col gap-2">
            <div class="flex items-center gap-2 text-blue-700 dark:text-blue-200 font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V10a2 2 0 012-2h2m10-4h-4m0 0V4m0 0v4"></path>
                </svg>
                Respuesta de la IA:
            </div>
            <p class="text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $respuesta }}</p>
        </div>
    @endif
</div>
