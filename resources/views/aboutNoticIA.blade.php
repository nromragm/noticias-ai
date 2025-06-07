<x-app-layout>
    <div class="max-w-4xl mx-auto py-10"> 
        <div class="max-w-2xl mx-auto py-10 px-4 bg-white dark:bg-gray-800 rounded-lg shadow">
            <h1 class="text-3xl font-bold mb-6 text-blue-700 dark:text-blue-300 text-center">¿Qué es NoticIA?</h1>
            <p class="mb-4 text-gray-700 dark:text-gray-200 text-lg">
                <strong>NoticIA</strong> es una aplicación web innovadora que utiliza inteligencia artificial para ofrecerte noticias relevantes, actualizadas y personalizadas en español e inglés. Nuestro objetivo es mantenerte informado de manera sencilla, rápida y con una experiencia moderna y atractiva.
            </p>
            <ul class="list-disc pl-6 mb-4 text-gray-700 dark:text-gray-200 space-y-3">
                <li><strong>Importación automática de noticias:</strong> Integramos fuentes de noticias nacionales e internacionales para que siempre tengas acceso a la información más reciente.</li>
                <li><strong>Valoración y comentarios:</strong> Puedes valorar las noticias y dejar tus comentarios para interactuar con otros usuarios.</li>
                <li><strong>Pregúntale a la IA:</strong> ¿Tienes dudas sobre una noticia? Nuestra IA responde tus preguntas y te ayuda a entender mejor la información.</li>
                <li><strong>Modo oscuro:</strong> Disfruta de una interfaz cómoda para tus ojos, tanto de día como de noche.</li>
                <li><strong>Panel de administración:</strong> Los administradores pueden gestionar, editar e importar noticias fácilmente.</li>
            </ul>
            <p class="text-gray-700 dark:text-gray-200">
                NoticIA está pensada para que cualquier persona pueda informarse, opinar y aprender, aprovechando el poder de la inteligencia artificial para mejorar la experiencia de lectura y comprensión de las noticias.
            </p>
            <div class="mt-8 text-center">
                <a href="{{ route('noticias.index') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white hover:text-white px-4 py-2 rounded shadow transition font-semibold inline-block">
                    Ir a Noticias
                </a>
            </div>
        </div>
    </div>
</x-app-layout>