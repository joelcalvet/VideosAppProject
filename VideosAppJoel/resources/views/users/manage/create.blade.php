@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Crear Usuari</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <form method="POST" action="{{ route('users.manage.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label text-gray-800">Nom</label>
                            <input type="text" name="name" id="name" class="form-control" data-qa="user-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label text-gray-800">Email</label>
                            <input type="email" name="email" id="email" class="form-control" data-qa="user-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label text-gray-800">Contrasenya</label>
                            <input type="password" name="password" id="password" class="form-control" data-qa="user-password" required>
                        </div>
                        <!-- Selector de permisos -->
                        <div class="mb-3">
                            <label class="form-label text-gray-800">Permisos</label>
                            @foreach ($permissions as $permission)
                                <div class="form-check">
                                    <input type="checkbox" name="permissions[]" id="permission-{{ $permission->id }}" value="{{ $permission->name }}" class="form-check-input" data-qa="permission-{{ $permission->name }}">
                                    <label for="permission-{{ $permission->id }}" class="form-check-label">{{ $permission->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
