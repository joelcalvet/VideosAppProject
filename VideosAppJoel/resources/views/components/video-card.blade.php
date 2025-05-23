@props([
    'video',
])

<div class="card">
    <div class="p-4">
        <h3 class="text-base font-semibold text-gray-800">{{ \Illuminate\Support\Str::limit($video->title, 50) }}</h3>
        <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($video->description, 100) }}</p>
        <p class="text-sm text-gray-600 mt-1">
            Publicat: {{ $video->published_at ? \Carbon\Carbon::parse($video->published_at)->format('d/m/Y') : 'No publicat' }}
        </p>
        <div class="mt-3 flex space-x-2">
            <x-button href="{{ route('videos.manage.edit', $video->id) }}" variant="warning" size="sm">
                <i class="fas fa-edit"></i> Editar
            </x-button>
            <x-button href="{{ route('videos.manage.delete', $video->id) }}" variant="danger" size="sm">
                <i class="fas fa-trash"></i> Eliminar
            </x-button>
        </div>
    </div>
</div>
