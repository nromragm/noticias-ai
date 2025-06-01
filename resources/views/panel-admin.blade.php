<x-app-layout>
    <div class="max-w-4xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-6 text-blue-700 dark:text-blue-300">Panel de Administrador</h1>

        <!-- Formulario para añadir noticia -->
        <livewire:admin.noticia-form />

        <hr class="my-8">

        <!-- Lista de noticias -->
        <livewire:admin.noticia-lista />
    </div>
</x-app-layout>