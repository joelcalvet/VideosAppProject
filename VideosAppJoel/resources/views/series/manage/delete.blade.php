@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Eliminar Sèrie</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <p class="text-center text-gray-700 mb-4">Estàs segur que vols eliminar la sèrie "{{ $serie->title }}"?</p>
                    <p class="text-center text-gray-700 mb-4">Aquesta acció desassignarà els vídeos associats (no s'eliminaran).</p>
                    <form method="POST" action="{{ route('series.manage.destroy', $serie->id) }}">
                        @csrf
                        @method('DELETE')
                        <div class="text-center">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                            <a href="{{ route('series.manage.index') }}" class="btn btn-secondary">Cancel·lar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
