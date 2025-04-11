@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Editar Sèrie</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <form method="POST" action="{{ route('series.manage.update', $serie->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label text-gray-800">Títol</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $serie->title) }}" data-qa="serie-title" required>
                            @error('title')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label text-gray-800">Descripció</label>
                            <textarea name="description" id="description" class="form-control" data-qa="serie-description" required>{{ old('description', $serie->description) }}</textarea>
                            @error('description')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label text-gray-800">Imatge</label>
                            <input type="file" name="image" id="image" class="form-control" data-qa="serie-image" accept="image/*">
                            @if ($serie->image)
                                <p class="text-sm text-gray-500 mt-2">
                                    Imatge actual: <img src="{{ Storage::url($serie->image) }}" alt="{{ $serie->title }}" style="max-height: 100px;">
                                </p>
                            @endif
                            @error('image')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="user_name" class="form-label text-gray-800">Nom de l’usuari</label>
                            <input type="text" name="user_name" id="user_name" class="form-control" value="{{ old('user_name', $serie->user_name) }}" data-qa="serie-user-name" required>
                            @error('user_name')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="user_photo_url" class="form-label text-gray-800">URL de la foto de l’usuari (opcional)</label>
                            <input type="url" name="user_photo_url" id="user_photo_url" class="form-control" value="{{ old('user_photo_url', $serie->user_photo_url) }}" data-qa="serie-user-photo-url">
                            @error('user_photo_url')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="published_at" class="form-label text-gray-800">Data de publicació</label>
                            <input type="datetime-local" name="published_at" id="published_at" class="form-control" value="{{ old('published_at', $serie->published_at ? Carbon::parse($serie->published_at)->format('Y-m-d\TH:i') : '') }}" data-qa="serie-published-at">
                            @error('published_at')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Actualitzar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
