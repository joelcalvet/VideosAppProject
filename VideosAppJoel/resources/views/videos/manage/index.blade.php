@extends('layouts.videosapp')

@section('content')
    <div class="container">
        <h1 class="mb-4">Gestió de Vídeos</h1>
        <a href="{{ route('videos.manage.create') }}" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Afegir Vídeo</a>
        <table class="table table-striped table-bordered shadow-sm">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Títol</th>
                <th>Descripció</th>
                <th>Publicat</th>
                <th>Accions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($videos as $video)
                <tr>
                    <td>{{ $video->id }}</td>
                    <td>{{ $video->title }}</td>
                    <td>{{ Str::limit($video->description, 50) }}</td>
                    <td>{{ $video->published_at ?? 'No publicat' }}</td>
                    <td>
                        <a href="{{ route('videos.manage.edit', $video->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                        <a href="{{ route('videos.manage.delete', $video->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
