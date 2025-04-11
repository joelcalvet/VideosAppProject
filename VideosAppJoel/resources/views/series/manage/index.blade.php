@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.videosapp')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Gestionar Sèries</h1>
        <div class="mb-4 text-center">
            <a href="{{ route('series.manage.create') }}" class="btn btn-primary">Crear Sèrie</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Títol</th>
                    <th class="py-2 px-4 border-b">Descripció</th>
                    <th class="py-2 px-4 border-b">Publicat</th>
                    <th class="py-2 px-4 border-b">Accions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($series as $serie)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ Str::limit($serie->title, 50) }}</td>
                        <td class="py-2 px-4 border-b">{{ Str::limit($serie->description, 100) }}</td>
                        <td class="py-2 px-4 border-b">{{ $serie->published_at ? Carbon::parse($serie->published_at)->format('d/m/Y') : 'No publicat' }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('series.manage.edit', $serie->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <a href="{{ route('series.manage.delete', $serie->id) }}" class="btn btn-sm btn-danger">Eliminar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-2 px-4 text-center text-gray-600">No hi ha sèries disponibles.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $series->links() }} <!-- Paginació -->
        </div>
    </div>
@endsection
