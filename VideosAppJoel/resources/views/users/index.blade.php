@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-[var(--font-size-xl)] font-bold mb-6 text-[var(--color-text)] text-center">Llista d’Usuaris</h1>

        <div class="mb-4">
            <input type="text" id="search" class="form-control w-full p-2 rounded border border-gray-300" placeholder="Cerca usuaris...">
        </div>

        @if($users->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($users as $user)
                    <x-ui.card :title="Str::limit($user->name, 50)">
                        <a href="{{ route('users.show', $user->id) }}" class="block hover:text-[var(--color-primary)] transition-colors duration-200">
                            <p>{{ Str::limit($user->email, 100) }}</p>
                            <p class="text-sm text-[var(--color-muted)] mt-2">
                                Creat: {{ Carbon::parse($user->created_at)->diffForHumans() }}
                            </p>
                        </a>
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

    <script>
        document.getElementById('search').addEventListener('input', function(e) {
            let filter = e.target.value.toLowerCase();
            document.querySelectorAll('x-ui-card').forEach(card => {
                let text = card.textContent.toLowerCase();
                card.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
@endsection
