@props([
    'serie',
])

<div class="card">
    <div class="p-4">
        <h3 class="text-base font-semibold text-gray-800">{{ \Illuminate\Support\Str::limit($serie->title, 50) }}</h3>
        <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($serie->description, 100) }}</p>
        <p class="text-sm text-gray-600 mt-1">
            Publicat: {{ $serie->published_at ? \Carbon\Carbon::parse($serie->published_at)->format('d/m/Y') : 'No publicat' }}
        </p>
        <div class="mt-3 flex space-x-2">
            <x-button href="{{ route('series.manage.edit', $serie->id) }}" variant="warning" size="sm">Editar</x-button>
            <x-button href="{{ route('series.manage.delete', $serie->id) }}" variant="danger" size="sm">Eliminar</x-button>
        </div>
    </div>
</div>
