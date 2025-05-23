@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-[var(--font-size-xl)] font-bold mb-6 text-[var(--color-text)] text-center">Llista de Vídeos</h1>

        @if (auth()->check() && auth()->user()->can('create-videos'))
            <div class="mb-16 text-center">
                <x-ui.button href="{{ route('videos.create') }}" color="primary" data-qa="create-video-button">
                    Crear Vídeo
                </x-ui.button>
            </div>
        @endif

        @if($videos->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($videos as $video)
                    <x-ui.card>
                        <a href="{{ route('videos.show', $video->id) }}" class="block rounded-t overflow-hidden shadow-md">
                            <div class="relative pb-[56.25%]">
                                <iframe
                                    class="absolute top-0 left-0 w-full h-full rounded-t"
                                    src="{{ str_replace('watch?v=', 'embed/', str_replace('m.youtube.com', 'www.youtube.com', $video->url)) }}"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </a>

                        <div class="p-4 flex flex-col">
                            <a href="{{ route('videos.show', $video->id) }}" class="text-[var(--color-text)] hover:text-[var(--color-primary)] transition-colors duration-200">
                                <h3 class="text-lg font-semibold mb-2">{{ Str::limit($video->title, 50) }}</h3>
                                <p class="text-sm text-[var(--color-text-muted)] flex-grow">
                                    {{ Str::limit($video->description, 100) }}
                                </p>
                                <p class="text-xs text-[var(--color-text-muted)] mt-2">
                                    Publicat: {{ $video->published_at ? Carbon::parse($video->published_at)->diffForHumans() : 'No publicat' }}
                                </p>
                            </a>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $videos->links() }}
            </div>
        @else
            <div class="text-center text-[var(--color-text-muted)] text-[var(--font-size-base)]">
                No hi ha vídeos disponibles.
            </div>
        @endif
    </div>
@endsection
