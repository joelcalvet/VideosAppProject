@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">{{ $serie->title }}</h1>

        <!-- Detalls de la sèrie -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4 mb-6">
            <p class="text-gray-600 mb-2">{{ $serie->description }}</p>
            <p class="text-sm text-gray-500">
                Publicat: {{ $serie->published_at ? Carbon::parse($serie->published_at)->diffForHumans() : 'No publicat' }}
            </p>
            @if ($serie->image)
                <img src="{{ Storage::url($serie->image) }}" alt="{{ $serie->title }}" class="img-fluid rounded mt-2" style="max-height: 300px;">
            @endif
            @if ($serie->user_id === auth()->id() || auth()->user()->can('manage series'))
                <div class="mt-4">
                    <a href="{{ route('series.edit', $serie->id) }}" class="btn btn-primary">Editar Sèrie</a>
                </div>
            @endif
        </div>

        <!-- Llista de vídeos -->
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Vídeos d'aquesta sèrie</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @forelse ($serie->videos as $video)
                <div class="col">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg h-100">
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
                            <a href="{{ route('videos.show', $video->id) }}" class="text-decoration-none">
                                <h5 class="text-lg font-semibold text-gray-800 mb-2 hover:text-blue-600">
                                    {{ Str::limit($video->title, 50) }}
                                </h5>
                                <p class="text-sm text-gray-600 flex-grow-1">
                                    {{ Str::limit($video->description, 100) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-2">
                                    Publicat: {{ $video->published_at ? Carbon::parse($video->published_at)->diffForHumans() : 'No publicat' }}
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-gray-600">No hi ha vídeos en aquesta sèrie.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
