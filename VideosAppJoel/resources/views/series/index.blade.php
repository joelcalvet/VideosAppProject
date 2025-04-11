@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Llista de Sèries</h1>
        <div class="mb-4 text-center">
            <a href="{{ route('series.create') }}" class="btn btn-primary">Crear Sèrie</a>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @forelse ($series as $serie)
                <div class="col">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg h-100">
                        <div class="p-4 flex flex-col">
                            <a href="{{ route('series.show', $serie->id) }}" class="text-decoration-none">
                                <h5 class="text-lg font-semibold text-gray-800 mb-2 hover:text-blue-600">
                                    {{ Str::limit($serie->title, 50) }}
                                </h5>
                                <p class="text-sm text-gray-600 flex-grow-1">
                                    {{ Str::limit($serie->description, 100) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-2">
                                    Publicat: {{ $serie->published_at ? Carbon::parse($serie->published_at)->diffForHumans() : 'No publicat' }}
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-gray-600">No hi ha sèries disponibles.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $series->links() }}
        </div>
    </div>
@endsection
