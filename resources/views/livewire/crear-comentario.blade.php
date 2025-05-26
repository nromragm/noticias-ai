<!-- resources/views/livewire/crear-comentario.blade.php -->
<form wire:submit.prevent="comentar" class="mb-6">
    <textarea wire:model.defer="comentario" class="w-full p-2 border rounded mb-2" placeholder="Escribe un comentario..."></textarea>
    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Publicar</button>
    @error('comentario') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
</form>
