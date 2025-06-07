<div>
    @auth
        <form wire:submit.prevent="valorar" class="flex items-center gap-2">
            <label for="valor" class="mr-2">Valora esta noticia:</label>
            <select wire:model="valor" id="valor" class="rounded border-gray-300" required>
                <option value="">Selecciona</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }} estrella{{ $i > 1 ? 's' : '' }}</option>
                @endfor
            </select>
            <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded">Valorar</button>
        </form>
        @if (session('success'))
            <div class="text-green-600 mt-2">{{ session('success') }}</div>
        @endif
    @else
        <p class="text-gray-500">Inicia sesión para valorar esta noticia.</p>
    @endauth

    <div class="mt-2">
        <strong>Valoración promedio:</strong>
        @if($promedio)
            {{ number_format($promedio, 1, ',', '') }} / 5
        @else
            Sin valoraciones aún.
        @endif
    </div>
</div>