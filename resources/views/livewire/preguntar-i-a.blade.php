<div class="mt-10 p-4 bg-white rounded shadow">
    <h3 class="text-xl font-semibold mb-4">Pregúntale a la IA sobre esta noticia</h3>

    <form wire:submit.prevent="preguntar">
        <textarea wire:model.defer="pregunta" class="w-full p-2 border rounded mb-2" placeholder="¿Qué quieres saber?"></textarea>
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Preguntar</button>
    </form>

    @if($respuesta)
        <div class="mt-4 p-3 bg-gray-100 rounded">
            <p><strong>Respuesta de la IA:</strong></p>
            <p>{{ $respuesta }}</p>
        </div>
    @endif
</div>
