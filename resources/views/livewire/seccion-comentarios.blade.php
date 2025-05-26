<div class="space-y-4">
    @forelse($comentarios as $comentario)
        <livewire:comentario-item :comentario="$comentario" :wire:key="$comentario->id" />
    @empty
        <p class="text-gray-500">Aún no hay comentarios.</p>
    @endforelse
</div>
