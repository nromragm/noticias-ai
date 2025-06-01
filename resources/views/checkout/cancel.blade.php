<x-app-layout>
    <div class="max-w-xl mx-auto mt-20 p-6 bg-white dark:bg-gray-800 rounded-lg shadow text-center">
        <h1 class="text-3xl font-bold text-red-600 mb-4">Pago cancelado</h1>
        <p class="mb-6 text-gray-700 dark:text-gray-300">
            Has cancelado el proceso de pago. Puedes seguir usando la app con las funcionalidades actuales.
        </p>
        <a href="{{ route('noticias.index') }}"
        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
            Volver al inicio
        </a>
    </div>
</x-app-layout>