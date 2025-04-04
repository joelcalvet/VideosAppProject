@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Gestionar Usuaris</h1>
        <div class="mb-4 text-center">
            <a href="{{ route('users.manage.create') }}" class="btn btn-primary">Crear usuari</a>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @forelse ($users as $user)
                <div class="col">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg h-100">
                        <div class="p-4 flex flex-col">
                            <h5 class="text-lg font-semibold text-gray-800 mb-2 hover:text-blue-600">
                                {{ Str::limit($user->name, 50) }}
                            </h5>
                            <p class="text-sm text-gray-600 flex-grow-1">
                                {{ Str::limit($user->email, 100) }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                Creat: {{ Carbon::parse($user->created_at)->diffForHumans() }}
                            </p>
                            <div class="mt-3">
                                <a href="{{ route('users.manage.edit', $user->id) }}" class="btn btn-sm btn-warning" data-qa="edit-user">Editar</a>
                                <a href="{{ route('users.manage.delete', $user->id) }}" class="btn btn-sm btn-danger" data-qa="delete-user">Eliminar</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-gray-600">No hi ha usuaris disponibles.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $users->links() }} <!-- Enllaços de paginació -->
        </div>
    </div>
@endsection
