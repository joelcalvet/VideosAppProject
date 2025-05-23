@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-[var(--font-size-xl)] font-bold mb-6 text-[var(--color-text)] text-center">Gestionar Sèries</h1>

        <div class="mb-16 text-center" style="margin-bottom: 4rem; text-align: center;">
            <x-ui.button href="{{ route('series.manage.create') }}" color="primary">
                Crear Sèrie
            </x-ui.button>
        </div>

        @if($series->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($series as $serie)
                    <x-ui.card :title="$serie->title">
                        <x-slot name="actions">
                            <x-ui.button href="{{ route('series.manage.edit', $serie->id) }}" color="warning" size="sm">
                                Editar
                            </x-ui.button>

                            <x-ui.button href="{{ route('series.manage.delete', $serie->id) }}" color="danger" size="sm">
                                Eliminar
                            </x-ui.button>
                        </x-slot>

                        <p>{{ Str::limit($serie->description, 100) }}</p>
                        <p class="text-sm text-[var(--color-muted)] mt-2">
                            Publicat: {{ $serie->published_at ? Carbon::parse($serie->published_at)->format('d/m/Y') : 'No publicat' }}
                        </p>
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
