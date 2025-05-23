<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplicació de Vídeos')</title>

    <!-- CDNs (sense canvis) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Estils actualitzats amb consistència */
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-size: 16px; /* Base size */
            line-height: 1.5;
            color: #333;
        }

        /* Navbar consistent */
        .navbar {
            background-color: #343a40;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 0.75rem 1rem;
        }

        .navbar-brand {
            font-size: 1.25rem; /* 20px */
            font-weight: 600;
            color: #ffffff !important;
            transition: color 0.2s ease;
        }

        .nav-link {
            font-size: 1rem; /* 16px */
            padding: 0.5rem 1rem !important;
            color: #ffffff !important;
            transition: color 0.2s ease;
        }

        .navbar-brand:hover, .nav-link:hover {
            color: #cccccc !important;
        }

        /* Contingut principal */
        main {
            flex: 1 0 auto;
            padding: 2rem 0;
            margin-bottom: 70px; /* Espai per al footer */
        }

        .container {
            max-width: 1200px;
            padding: 0 1.5rem;
        }

        /* Footer consistent */
        .footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 1.25rem 0;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }

        /* Botó de logout */
        .btn-logout {
            color: #ffffff !important;
            background: none;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
            font-size: 1rem;
            transition: color 0.2s ease;
        }

        .btn-logout:hover {
            color: #cccccc !important;
        }

        /* Superposició per a modals */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050;
        }

        /* Espaiat consistent */
        .py-section {
            padding: 3rem 0;
        }

        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mb-3 { margin-bottom: 1.5rem; }
        .mb-4 { margin-bottom: 2rem; }
    </style>
</head>
<body>

<!-- Barra de navegació (sense canvis estructurals) -->
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

<!-- Contingut principal -->
<main class="container py-5">
    @yield('content')
</main>

<!-- Peu de pàgina -->
<footer class="footer">
    <div class="container">
        © {{ date('Y') }} Aplicació de Vídeos - Tots els drets reservats
    </div>
</footer>

<!-- Scripts (sense canvis) -->
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

@if(session('success'))
    <x-alert type="success" message="{{ session('success') }}" />
@endif

@if(session('error'))
    <x-alert type="error" message="{{ session('error') }}" />
@endif
</body>
</html>
