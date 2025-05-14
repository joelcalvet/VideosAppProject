@extends('layouts.videosapp')

@section('title', 'Notificacions')

@section('content')
    <h1 class="mb-4">Notificacions</h1>

    <div id="notifications">
        @forelse ($notifications as $notification)
            <div class="alert {{ is_null($notification->read_at) ? 'alert-info' : 'alert-secondary' }}">
                <strong>{{ $notification->data['title'] }}</strong>
                <div>Creat per {{ $notification->data['creator_name'] }}</div>
                <div>
                    <small>
                        @if ($notification->read_at)
                            Llegida el {{ $notification->read_at->format('d/m/Y H:i') }}
                        @else
                            <span class="text-danger">No llegida</span>
                        @endif
                    </small>
                </div>

                @if (is_null($notification->read_at))
                    <form action="{{ route('notificacions.read', $notification->id) }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">Marcar com a llegida</button>
                    </form>
                @else
                    <div class="mt-2">
                        <span class="badge bg-success">Llegida</span>
                    </div>
                @endif
            </div>
        @empty
            <p>No tens notificacions.</p>
        @endforelse
    </div>

    <form action="{{ route('notificacions.readAll') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-warning mt-3">Marcar totes com a llegides</button>
    </form>

@endsection
