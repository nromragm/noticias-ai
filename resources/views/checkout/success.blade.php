<x-app-layout>
    <div class="min-h-[60vh] flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-2xl font-bold">¡Gracias por tu compra!</h1>
            <p>Ya puedes usar el modelo GPT-4.5 para tus preguntas.</p>
            <a href="{{ route('noticias.index') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white hover:text-white px-4 py-2 rounded shadow transition font-semibold inline-block">
                Volver al inicio
            </a>
        </div>
    </div>
</x-app-layout>