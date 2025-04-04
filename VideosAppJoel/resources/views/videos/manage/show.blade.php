@extends('layouts.videosapp')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h1 class="h4 mb-0">{{ $video->title }}</h1>
            </div>
            <div class="card-body">
                <p>{{ $video->description ?? 'Sense descripció' }}</p>
                <a href="{{ $video->url }}" target="_blank" class="btn btn-primary"><i class="fas fa-play"></i> Veure Vídeo</a>
                <p class="mt-3 text-muted">Publicat el: {{ $video->published_at ?? 'No publicat' }}</p>
                <a href="{{ route('videos.index') }}" class="btn btn-secondary">Tornar</a>
            </div>
        </div>
    </div>
@endsection
