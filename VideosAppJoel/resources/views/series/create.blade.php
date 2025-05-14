@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Crear Sèrie</h1>
        <form action="{{ route('series.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Títol</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" data-qa="series-title-input">
                @error('title')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripció</label>
                <textarea name="description" id="description" class="form-control" data-qa="series-description-input">{{ old('description') }}</textarea>
                @error('description')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Imatge</label>
                <input type="file" name="image" id="image" class="form-control" data-qa="series-image-input">
                @error('image')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="video_ids" class="form-label">Selecciona vídeos (opcional)</label>
                <select name="video_ids[]" id="video_ids" class="form-control" multiple data-qa="video-ids-select">
                    @foreach ($videos as $video)
                        <option value="{{ $video->id }}">{{ $video->title }}</option>
                    @endforeach
                </select>
                @error('video_ids')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary" data-qa="series-submit-button">Crear Sèrie</button>
        </form>
    </div>
@endsection
