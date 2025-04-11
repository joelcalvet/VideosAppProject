@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Crear una nova sèrie</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <form method="POST" action="{{ route('series.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label text-gray-800">Títol</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                            @error('title')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label text-gray-800">Descripció</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label text-gray-800">Imatge (opcional)</label>
                            <input type="file" name="image" id="image" class="form-control">
                            @error('image')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="published_at" class="form-label text-gray-800">Data de publicació (opcional)</label>
                            <input type="datetime-local" name="published_at" id="published_at" class="form-control" value="{{ old('published_at') }}">
                            @error('published_at')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Crear Sèrie</button>
                            <a href="{{ route('series.index') }}" class="btn btn-secondary">Cancel·lar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
