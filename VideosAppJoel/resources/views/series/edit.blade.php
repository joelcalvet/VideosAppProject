@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Editar Sèrie</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <!-- Formulari per editar la sèrie -->
                    <form method="POST" action="{{ route('series.update', $serie->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label text-gray-800">Títol</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $serie->title) }}" required>
                            @error('title')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label text-gray-800">Descripció</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $serie->description) }}</textarea>
                            @error('description')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label text-gray-800">Imatge (opcional)</label>
                            <input type="file" name="image" id="image" class="form-control">
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
                            <label for="published_at" class="form-label text-gray-800">Data de publicació (opcional)</label>
                            <input type="datetime-local" name="published_at" id="published_at" class="form-control" value="{{ old('published_at', $serie->published_at ? Carbon::parse($serie->published_at)->format('Y-m-d\TH:i') : '') }}">
                            @error('published_at')
                            <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Actualitzar Sèrie</button>
                            <a href="{{ route('series.show', $serie->id) }}" class="btn btn-secondary">Cancel·lar</a>
                        </div>
                    </form>

                    <!-- Secció per afegir vídeos, només visible per a admins o propietaris -->
                    @if (auth()->user()->can('manage series') || $serie->user_id === auth()->id())
                        <h2 class="text-xl font-bold mt-6 text-gray-800">Afegir vídeos a la sèrie</h2>
                        <form method="POST" action="{{ route('series.addVideo', $serie->id) }}" class="mt-4">
                            @csrf
                            <div class="mb-3">
                                <label for="video_id" class="form-label text-gray-800">Seleccionar vídeo</label>
                                <select name="video_id" id="video_id" class="form-control">
                                    <option value="">-- Selecciona un vídeo --</option>
                                    @foreach ($availableVideos as $video)
                                        <option value="{{ $video->id }}">{{ Str::limit($video->title, 50) }}</option>
                                    @endforeach
                                </select>
                                @error('video_id')
                                <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Afegir vídeo</button>
                            </div>
                        </form>

                        <!-- Llista de vídeos actuals de la sèrie -->
                        <h3 class="text-lg font-semibold mt-6 text-gray-800">Vídeos actuals</h3>
                        @if ($serie->videos->isEmpty())
                            <p class="text-gray-600">Aquesta sèrie no té vídeos encara.</p>
                        @else
                            <ul class="list-group mt-2">
                                @foreach ($serie->videos as $video)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ Str::limit($video->title, 50) }}
                                        <span class="text-sm text-gray-500">
                                            {{ $video->published_at ? Carbon::parse($video->published_at)->diffForHumans() : 'No publicat' }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
