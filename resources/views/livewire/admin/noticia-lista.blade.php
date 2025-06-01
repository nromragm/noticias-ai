<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
    @if (session()->has('success'))
        <div class="mb-4 text-green-700 bg-green-100 dark:bg-green-900 dark:text-green-200 p-2 rounded">
            {{ session('success') }}
        </div>
    @endif
    <h2 class="text-lg font-semibold mb-4">Noticias existentes</h2>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <th class="py-2 text-left">Título</th>
                <th class="py-2 text-left">Categoría</th>
                <th class="py-2 text-left">Fecha</th>
                <th class="py-2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($noticias as $noticia)
                <tr class="border-b border-gray-100 dark:border-gray-700">
                    <td class="py-2">{{ $noticia->titulo }}</td>
                    <td class="py-2">{{ $noticia->categoria }}</td>
                    <td class="py-2">{{ $noticia->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-2 text-right">
                        <button wire:click="delete({{ $noticia->id }})" class="text-red-600 hover:underline">Eliminar</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-4 text-center text-gray-500">No hay noticias.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>