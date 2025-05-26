<div class="p-4 bg-gray-100 rounded shadow flex justify-between items-start">
    <div>
        <p class="text-sm text-gray-500">
            {{ $comentario->user?->name ?? 'Comentario eliminado' }}
            ·
            {{ $comentario->created_at ? $comentario->created_at->diffForHumans() : '' }}
        </p>
        <p>{{ $comentario->comentario }}</p>
    </div>

    @if(auth()->id() === $comentario->user_id)
        <button wire:click="eliminar" class="text-red-500 hover:underline text-sm">
            Eliminar
        </button>
    @endif
</div>
