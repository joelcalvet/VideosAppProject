@extends('layouts.videosapp')

@section('content')
    <div class="container">
        <h1>Editar Vídeo</h1>
        <form action="{{ route('videos.manage.update', $video->id) }}" method="POST" data-qa="edit-video-form">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">Títol</label>
                <input type="text" name="title" class="form-control" value="{{ $video->title }}" required data-qa="video-title-input">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripció</label>
                <textarea name="description" class="form-control" rows="3" data-qa="video-description-input">{{ $video->description }}</textarea>
            </div>
            <div class="mb-3">
                <label for="url" class="form-label">URL del Vídeo</label>
                <input type="url" name="url" class="form-control" value="{{ $video->url }}" required data-qa="video-url-input">
            </div>
            <div class="mb-3">
                <label for="published_at" class="form-label">Data de Publicació</label>
                <input type="date" name="published_at" class="form-control"
                       value="{{ $video->published_at ? $video->published_at->format('Y-m-d') : '' }}" data-qa="video-published-at-input">
            </div>
            <button type="submit" class="btn btn-primary" data-qa="update-video-button">Actualitzar Vídeo</button>
            <a href="{{ route('videos.manage.index') }}" class="btn btn-secondary" data-qa="cancel-button">Cancel·lar</a>
        </form>
    </div>
@endsection
