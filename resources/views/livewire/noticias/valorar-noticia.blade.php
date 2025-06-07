<div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow">
    @auth
        <form wire:submit.prevent="valorar" class="flex items-center gap-2">
            <label for="valor" class="mr-2 text-gray-700 dark:text-gray-200">Valora esta noticia:</label>
            <select wire:model="valor" id="valor" class="rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700" required>
                <option value="">Selecciona</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }} estrella{{ $i > 1 ? 's' : '' }}</option>
                @endfor
            </select>
            <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 transition">Valorar</button>
        </form>
        @if (session('success'))
            <div class="text-green-600 dark:text-green-300 mt-2">{{ session('success') }}</div>
        @endif
    @else
        <p class="text-gray-500 dark:text-gray-300">Inicia sesión para valorar esta noticia.</p>
    @endauth

    <div class="mt-2 flex items-center gap-1">
        <strong class="text-gray-700 dark:text-gray-200">Valoración promedio: </strong>
        @if($promedio)
            <span class="ml-1">
                {{ fmod($promedio, 1) == 0 ? number_format($promedio, 0, ',', '') : number_format($promedio, 1, ',', '') }} / 5
            </span>
            <svg class="inline w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <polygon points="10,1 12.59,7.36 19.51,7.64 14,12.26 15.82,19.02 10,15.27 4.18,19.02 6,12.26 0.49,7.64 7.41,7.36"/>
            </svg>
        @else
            <span class="ml-1">Sin valoraciones aún.</span>
        @endif
    </div>
</div>