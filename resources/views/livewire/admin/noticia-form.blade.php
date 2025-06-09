<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6">
    {{-- Mensaje de éxito o error --}}
    @if (session('success') || session('error'))
        <div class="mb-4 p-2 rounded text-center font-semibold
            {{ session('success') ? 'text-green-700 bg-green-100 dark:bg-green-900 dark:text-green-200' : 'text-red-700 bg-red-100 dark:bg-red-900 dark:text-red-200' }}">
            {{ session('success') ?? session('error') }}
        </div>
    @endif

    <h2 class="text-xl font-bold mb-4 text-blue-700 dark:text-blue-300 flex items-center gap-2">
        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h4l2-2 2 2h4a2 2 0 012 2v12a2 2 0 01-2 2z"/>
        </svg>
        Gestión de Noticias
    </h2>

    {{-- Importar noticias desde la API --}}
    <div class="flex flex-col items-center mb-6">
        <div class="mb-4 text-center">
            <span class="font-semibold text-gray-700 dark:text-gray-200">Importar noticias desde la API</span>
        </div>
        <div class="flex flex-wrap gap-2 justify-center">
            <button wire:click="importarNoticias('es')" type="button"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition">
                Importar noticias españolas
            </button>
            <button wire:click="importarNoticias('en')" type="button"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition">
                Importar noticias inglesas
            </button>
            <div wire:loading wire:target="importarNoticias" class="flex items-center gap-2 text-blue-700 bg-blue-100 dark:bg-blue-900 dark:text-blue-200 px-3 py-2 rounded shadow">
                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                Importando noticias...
            </div>
        </div>
    </div>

    <hr class="mb-6 border-blue-200 dark:border-blue-700">

    <form wire:submit.prevent="save" class="space-y-6">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Añadir o editar noticia</h3>
        <div>
            <label class="block text-sm font-medium mb-1">Título</label>
            <input type="text" wire:model.defer="titulo" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100 focus:ring focus:ring-blue-200" required>
            @error('titulo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Descripción</label>
            <input type="text" wire:model.defer="descripcion" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100 focus:ring focus:ring-blue-200" required>
            @error('descripcion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Contenido</label>
            <textarea wire:model.defer="contenido" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100 focus:ring focus:ring-blue-200" rows="4" required></textarea>
            @error('contenido') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="flex flex-col md:flex-row gap-4">
           <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Categoría</label>
                <select wire:model.defer="categoria" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100 focus:ring focus:ring-blue-200" required>
                    <option value="">Selecciona una categoría</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
                @error('categoria') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Fuente</label>
                <input type="text" wire:model.defer="source" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100 focus:ring focus:ring-blue-200">
                @error('source') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">URL Imagen</label>
            <input type="url" wire:model.defer="urlImg" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100 focus:ring focus:ring-blue-200">
            @error('urlImg') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">URL Video (opcional)</label>
            <input type="url" wire:model.defer="urlVideo" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100 focus:ring focus:ring-blue-200">
            @error('urlVideo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow transition font-semibold">
                Guardar Noticia
            </button>
        </div>
    </form>
</div>