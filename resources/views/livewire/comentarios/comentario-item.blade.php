<div class="p-4 bg-gray-100 dark:bg-gray-800 rounded shadow flex justify-between items-start">
    <div>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ $comentario->user?->name ?? 'Comentario eliminado' }}
            ·
            {{ $comentario->created_at ? $comentario->created_at->diffForHumans() : '' }}
        </p>
        <p class="text-gray-900 dark:text-gray-100">{{ $comentario->comentario }}</p>
    </div>

    @if(
        $comentario->user // El comentario tiene usuario (no está eliminado)
        && (auth()->id() === $comentario->user_id || (auth()->check() && auth()->user()->isAdmin()))
    )
        <button wire:click="eliminar" class="text-red-500 hover:underline text-sm">
            Eliminar
        </button>
    @endif
</div>