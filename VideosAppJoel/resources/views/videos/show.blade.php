@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">{{ $video->title }}</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    {{-- Descripció del vídeo --}}
                    <p class="text-lg text-gray-700 mb-4">{{ $video->description ?? 'Sense descripció' }}</p>

                    {{-- Informació de publicació --}}
                    <div class="text-sm text-gray-500 mb-6">
                        <p>
                            <span class="font-semibold">Publicat:</span>
                            {{ $video->published_at ? Carbon::parse($video->published_at)->format('jS \o\f F, Y') : 'No publicat' }}
                        </p>
                        <p>{{ $video->published_at ? Carbon::parse($video->published_at)->diffForHumans() : 'No publicat' }}</p>
                    </div>

                    {{-- Contenidor del vídeo --}}
                    <div class="mt-6 flex justify-center">
                        <iframe
                            class="rounded-lg shadow-md"
                            width="560"
                            height="315"
                            src="{{ str_replace('watch?v=', 'embed/', str_replace('m.youtube.com', 'www.youtube.com', $video->url)) }}"
                            allow="autoplay; encrypted-media"
                            allowfullscreen>
                        </iframe>
                    </div>

                    {{-- Botó per tornar a la llista --}}
                    <div class="mt-4 text-center">
                        <a href="{{ route('videos.index') }}" class="btn btn-secondary">Tornar a la llista</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
