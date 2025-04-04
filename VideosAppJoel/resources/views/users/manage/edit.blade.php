@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Editar Usuari</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <form method="POST" action="{{ route('users.manage.update', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label text-gray-800">Nom</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" data-qa="user-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label text-gray-800">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" data-qa="user-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label text-gray-800">Contrasenya (opcional)</label>
                            <input type="password" name="password" id="password" class="form-control" data-qa="user-password">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Actualitzar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
