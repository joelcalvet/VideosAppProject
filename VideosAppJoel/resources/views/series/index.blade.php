@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-[var(--font-size-xl)] font-bold mb-6 text-[var(--color-text)] text-center">Llista de Sèries</h1>

        <div class="mb-16 text-center">
            <x-ui.button href="{{ route('series.create') }}" color="primary">
                Crear Sèrie
            </x-ui.button>
        </div>

        @if($series->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($series as $serie)
                    <x-ui.card :title="Str::limit($serie->title, 50)">
                        <a href="{{ route('series.show', $serie->id) }}" class="block hover:text-[var(--color-primary)] transition-colors duration-200">
                            <p>{{ Str::limit($serie->description, 100) }}</p>
                            <p class="text-sm text-[var(--color-muted)] mt-2">
                                Publicat: {{ $serie->published_at ? Carbon::parse($serie->published_at)->diffForHumans() : 'No publicat' }}
                            </p>
                        </a>
                    </x-ui.card>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $series->links() }}
            </div>
        @else
            <div class="text-center text-[var(--color-muted)] text-[var(--font-size-base)]">
                No hi ha sèries disponibles.
            </div>
        @endif
    </div>
@endsection
