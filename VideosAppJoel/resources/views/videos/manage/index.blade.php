@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-[var(--font-size-xl)] font-bold mb-6 text-[var(--color-text)] text-center">Gestió de Vídeos</h1>

        <div class="mb-8 text-center">
            <x-ui.button href="{{ route('videos.manage.create') }}" color="primary" size="md" class="inline-flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i> Afegir Vídeo
            </x-ui.button>
        </div>

        @if(session('success'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                class="mb-6 p-4 rounded shadow text-green-800 bg-green-100"
            >
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                class="mb-6 p-4 rounded shadow text-red-800 bg-red-100"
            >
                {{ session('error') }}
            </div>
        @endif

        @if($videos->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($videos as $video)
                    <x-ui.card class="flex flex-col justify-between">
                        <div>
                            <h2 class="text-lg font-semibold mb-2 text-[var(--color-text)] truncate">{{ $video->title }}</h2>
                            <p class="text-sm text-[var(--color-text-muted)] mb-2">{{ Str::limit($video->description, 100) }}</p>
                            <p class="text-xs text-[var(--color-text-muted)]">Publicat: {{ $video->published_at ? Carbon::parse($video->published_at)->diffForHumans() : 'No publicat' }}</p>
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <x-ui.button href="{{ route('videos.manage.edit', $video->id) }}" color="warning" size="sm" class="flex-1">
                                <i class="fas fa-edit mr-1"></i> Editar
                            </x-ui.button>
                            <form action="{{ route('videos.manage.delete', $video->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Estàs segur que vols eliminar aquest vídeo?');">
                                @csrf
                                @method('DELETE')
                                <x-ui.button type="submit" color="danger" size="sm" class="w-full">
                                    <i class="fas fa-trash mr-1"></i> Eliminar
                                </x-ui.button>
                            </form>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>
        @else
            <p class="text-center text-[var(--color-text-muted)] text-[var(--font-size-base)]">
                No hi ha vídeos disponibles.
            </p>
        @endif
    </div>
@endsection
