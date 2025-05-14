@extends('layouts.videosapp')

@section('title', 'Crear Vídeo')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Crear Vídeo</h1>

        <!-- Missatge d'èxit -->
        @if (session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('videos.store') }}" method="POST">
            @csrf

            <!-- Títol -->
            <div class="mb-3">
                <label for="title" class="form-label">Títol</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Descripció -->
            <div class="mb-3">
                <label for="description" class="form-label">Descripció</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- URL -->
            <div class="mb-3">
                <label for="url" class="form-label">URL del vídeo</label>
                <input type="url" name="url" id="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url') }}">
                @error('url')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Data de publicació -->
            <div class="mb-3">
                <label for="published_at" class="form-label">Data de publicació (opcional)</label>
                <input type="datetime-local" name="published_at" id="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at') }}">
                @error('published_at')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Sèrie -->
            <div class="mb-3">
                <label for="serie_id" class="form-label">Sèrie (opcional)</label>
                <select name="serie_id" id="serie_id" class="form-select @error('serie_id') is-invalid @enderror">
                    <option value="">-- Sense sèrie --</option>
                    @foreach ($series as $serie)
                        <option value="{{ $serie->id }}" {{ old('serie_id') == $serie->id ? 'selected' : '' }}>{{ $serie->title }}</option>
                    @endforeach
                </select>
                @error('serie_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" onclick="this.disabled=true; this.form.submit();">Crear Vídeo</button>
        </form>
    </div>
@endsection
