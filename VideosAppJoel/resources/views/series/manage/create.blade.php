@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Crear Sèrie</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <form method="POST" action="{{ route('series.manage.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label text-gray-800">Títol</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" data-qa="serie-title" required>
                            @error('title')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label text-gray-800">Descripció</label>
                            <textarea name="description" id="description" class="form-control" data-qa="serie-description" required>{{ old('description') }}</textarea>
                            @error('description')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label text-gray-800">Imatge</label>
                            <input type="file" name="image" id="image" class="form-control" data-qa="serie-image" accept="image/*">
                            @error('image')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="user_name" class="form-label text-gray-800">Nom de l’usuari</label>
                            <input type="text" name="user_name" id="user_name" class="form-control" value="{{ old('user_name', auth()->user()->name) }}" data-qa="serie-user-name" required>
                            @error('user_name')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="user_photo_url" class="form-label text-gray-800">URL de la foto de l’usuari (opcional)</label>
                            <input type="url" name="user_photo_url" id="user_photo_url" class="form-control" value="{{ old('user_photo_url') }}" data-qa="serie-user-photo-url">
                            @error('user_photo_url')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="published_at" class="form-label text-gray-800">Data de publicació</label>
                            <input type="datetime-local" name="published_at" id="published_at" class="form-control" value="{{ old('published_at') }}" data-qa="serie-published-at">
                            @error('published_at')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
