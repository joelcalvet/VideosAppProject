<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplicació de Vídeos')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background-color: #343a40;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand, .nav-link {
            color: #ffffff !important;
            transition: color 0.2s ease;
        }
        .navbar-brand:hover, .nav-link:hover {
            color: #cccccc !important;
        }
        main {
            flex: 1 0 auto;
            padding-bottom: 70px;
        }
        .footer {
            flex-shrink: 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #343a40;
            color: #ffffff;
            padding: 1rem 0;
            text-align: center;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn-logout {
            color: #ffffff !important;
            background: none;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
        }
        .btn-logout:hover {
            color: #cccccc !important;
        }
    </style>
    @vite(['resources/js/app.js'])
</head>
<body>

<!-- Barra de navegació -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('videos.index') }}">Vídeos App</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('videos.index') }}">Vídeos</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('series.index') }}">Sèries</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Usuaris</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('notificacions') }}">Notificacions</a></li>
                    @can('manage videos')
                        <li class="nav-item"><a class="nav-link" href="{{ route('videos.manage.index') }}">Gestionar Vídeos</a></li>
                    @endcan
                    @can('manage series')
                        <li class="nav-item"><a class="nav-link" href="{{ route('series.manage.index') }}">Gestionar Sèries</a></li>
                    @endcan
                    @can('manage users')
                        <li class="nav-item"><a class="nav-link" href="{{ route('users.manage.index') }}">Gestionar Usuaris</a></li>
                    @endcan
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Sortir</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Iniciar Sessió</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container py-5">
    @yield('content')
</main>

<!-- Peu de pàgina -->
<footer class="footer">
    <div class="container">
        © {{ date('Y') }} Aplicació de Vídeos - Tots els drets reservats
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

@auth
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userId = {{ auth()->id() }};
            const isSuperAdmin = {{ auth()->user()->hasRole('super-admin') ? 'true' : 'false' }};

            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 5000
            };

            function appendNotification(video, type = 'success') {
                const notificationDiv = document.createElement('div');
                notificationDiv.classList.add('alert', `alert-${type}`);
                notificationDiv.innerHTML = `<strong>${video.title}</strong><div>Creat per ${video.creator_name}</div>`;

                const container = document.getElementById('notifications');
                if (container) {
                    container.prepend(notificationDiv);
                }
            }

            if (window.Echo) {
                if (isSuperAdmin) {
                    window.Echo.private('videos.admin')
                        .listen('.VideoCreated', (e) => {
                            console.log('[Admin] Event rebut:', e);
                            toastr.info(`Nou vídeo creat: ${e.video.title} per ${e.video.creator_name}`, 'Notificació Admin');
                            appendNotification(e.video, 'info');
                        });
                }

                window.Echo.private(`videos.user.${userId}`)
                    .listen('.VideoCreated', (e) => {
                        console.log('[Usuari] Event rebut:', e);
                        toastr.success(`Nou vídeo creat: ${e.video.title} per ${e.video.creator_name}`, 'Notificació Personal');
                        appendNotification(e.video, 'success');
                    });
            } else {
                console.error('Laravel Echo no està carregat.');
            }
        });
    </script>
@endauth

@stack('scripts')
</body>
</html>
