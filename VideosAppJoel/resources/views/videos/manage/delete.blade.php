@extends('layouts.videosapp')

@section('content')
    <div class="container">
        <h1>Esborrar Vídeo</h1>
        <p>Estàs segur que vols esborrar el vídeo "{{ $video->title }}"?</p>
        <form action="{{ route('videos.manage.destroy', $video->id) }}" method="POST" data-qa="delete-video-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" data-qa="confirm-delete-button">Sí, Esborrar</button>
            <a href="{{ route('videos.manage.index') }}" class="btn btn-secondary" data-qa="cancel-delete-button">Cancel·lar</a>
        </form>
    </div>
@endsection
