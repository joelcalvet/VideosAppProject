@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Eliminar Usuari</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4 text-center">
                    <p class="text-gray-600 mb-4">Estàs segur que vols eliminar l’usuari <strong>{{ $user->name }}</strong>?</p>
                    <form method="POST" action="{{ route('users.manage.destroy', $user->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" data-qa="confirm-delete">Eliminar</button>
                        <a href="{{ route('users.manage.index') }}" class="btn btn-secondary ms-2">Cancel·lar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
