@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-[var(--font-size-xl)] font-bold mb-6 text-[var(--color-text)] text-center">Gestionar Usuaris</h1>

        <div class="mb-16 text-center">
            <x-ui.button href="{{ route('users.manage.create') }}" color="primary">
                Crear usuari
            </x-ui.button>
        </div>

        @if($users->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($users as $user)
                    <x-ui.card :title="Str::limit($user->name, 50)">
                        <p>{{ Str::limit($user->email, 100) }}</p>
                        <p class="text-sm text-[var(--color-muted)] mt-2">
                            Creat: {{ Carbon::parse($user->created_at)->diffForHumans() }}
                        </p>

                        <x-slot name="actions">
                            <x-ui.button href="{{ route('users.manage.edit', $user->id) }}" color="warning" size="sm">
                                Editar
                            </x-ui.button>

                            <x-ui.button href="{{ route('users.manage.delete', $user->id) }}" color="danger" size="sm" onclick="return confirm('Segur que vols eliminar aquest usuari?')">
                                Eliminar
                            </x-ui.button>
                        </x-slot>
                    </x-ui.card>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $users->links() }}
            </div>
        @else
            <div class="text-center text-[var(--color-muted)] text-[var(--font-size-base)]">
                No hi ha usuaris disponibles.
            </div>
        @endif
    </div>
@endsection
