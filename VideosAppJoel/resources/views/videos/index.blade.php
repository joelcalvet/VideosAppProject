@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Llista de Vídeos</h1>
        @if (auth()->check() && auth()->user()->can('create-videos'))
            <a href="{{ route('videos.create') }}" class="btn btn-primary mb-3" data-qa="create-video-button">Crear Vídeo</a>
        @endif
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @forelse ($videos as $video)
                <div class="col">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg h-100">
                        <!-- Contenidor del vídeo amb iframe -->
                        <a href="{{ route('videos.show', $video->id) }}">
                            <div class="position-relative" style="padding-bottom: 56.25%;">
                                <iframe
                                    class="position-absolute top-0 start-0 w-100 h-100 rounded-top"
                                    src="{{ str_replace('watch?v=', 'embed/', str_replace('m.youtube.com', 'www.youtube.com', $video->url)) }}"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </a>

                        <div class="p-4 flex flex-col">
                            <!-- Enllaç al text per anar al show -->
                            <a href="{{ route('videos.show', $video->id) }}" class="text-decoration-none">
                                <!-- Títol del vídeo -->
                                <h5 class="text-lg font-semibold text-gray-800 mb-2 hover:text-blue-600">
                                    {{ Str::limit($video->title, 50) }}
                                </h5>

                                <!-- Descripció curta -->
                                <p class="text-sm text-gray-600 flex-grow-1">
                                    {{ Str::limit($video->description, 100) }}
                                </p>

                                <!-- Data de publicació -->
                                <p class="text-xs text-gray-500 mt-2">
                                    Publicat: {{ $video->published_at ? Carbon::parse($video->published_at)->diffForHumans() : 'No publicat' }}
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-gray-600">No hi ha vídeos disponibles.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
