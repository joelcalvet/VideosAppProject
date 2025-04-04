@extends('layouts.videosapp')

@section('content')
    <div class="container">
        <h1 class="mb-4">Afegir Nou Vídeo</h1>
        <form action="{{ route('videos.manage.store') }}" method="POST" class="shadow-sm p-4 bg-white rounded">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Títol</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripció</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="url" class="form-label">URL del Vídeo</label>
                <input type="url" name="url" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="published_at" class="form-label">Data de Publicació</label>
                <input type="date" name="published_at" class="form-control">
            </div>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Desar</button>
            <a href="{{ route('videos.manage.index') }}" class="btn btn-secondary">Cancel·lar</a>
        </form>
    </div>
@endsection
