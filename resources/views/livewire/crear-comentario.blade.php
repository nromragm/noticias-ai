<!-- resources/views/livewire/crear-comentario.blade.php -->
<form wire:submit.prevent="comentar" class="mb-6">
    <textarea wire:model.defer="comentario" class="w-full p-2 border rounded mb-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-700 placeholder-gray-500 dark:placeholder-gray-400" placeholder="Escribe un comentario..."></textarea>
    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Publicar</button>
    @error('comentario') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
</form>