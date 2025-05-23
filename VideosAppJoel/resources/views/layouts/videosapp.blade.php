<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplicació de Vídeos')</title>

    <!-- CDNs -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Estils actualitzats */
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-size: 16px;
            line-height: 1.5;
            color: #333;
            padding-bottom: 70px;
        }

        /* Navbar millorada */
        .navbar {
            background-color: #343a40;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 0.75rem 1rem;
        }

        .navbar-brand {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff !important;
        }

        .nav-link {
            font-size: 1rem;
            padding: 0.5rem 1rem !important;
            color: #ffffff !important;
        }

        .navbar-brand:hover, .nav-link:hover {
            color: #cccccc !important;
        }

        /* Menú desplegable fixat - SOLUCIÓ DEFINITIVA */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                position: fixed;
                top: 56px;
                left: 0;
                right: 0;
                background-color: #343a40;
                padding: 1rem;
                z-index: 1000;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                max-height: calc(100vh - 56px);
                overflow-y: auto;
                transition: none !important;
            }

            /* Evita el parpadeig */
            .navbar-collapse:not(.show) {
                display: none !important;
            }
        }

        /* Contingut principal */
        main {
            flex: 1;
            padding: 2rem 0;
        }

        .container {
            max-width: 1200px;
            padding: 0 1.5rem;
        }

        /* Footer */
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
        }

        /* Espaiat consistent */
        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mb-3 { margin-bottom: 1.5rem; }
    </style>
</head>
<body>

<!-- Barra de navegació -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('videos.index') }}">Vídeos App</a>
        <button class="navbar-toggler" type="button" id="navbarToggler">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse" id="navbarNav">
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

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

<script>
    // SOLUCIÓ DEFINITIVA PER AL MENÚ
    document.addEventListener('DOMContentLoaded', function() {
        const navbarToggler = document.getElementById('navbarToggler');
        const navbarCollapse = document.getElementById('navbarNav');

        // Control manual del menú
        navbarToggler.addEventListener('click', function() {
            navbarCollapse.classList.toggle('show');

            // Tanca altres menús oberts
            document.querySelectorAll('.navbar-collapse.show').forEach(function(menu) {
                if (menu !== navbarCollapse) {
                    menu.classList.remove('show');
                }
            });
        });

        // Tanca el menú en fer clic a un enllaç (només en mòbil)
        document.querySelectorAll('.nav-link').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    navbarCollapse.classList.remove('show');
                }
            });
        });

        // Tanca el menú en fer clic fora
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.navbar') && !event.target.closest('.navbar-collapse')) {
                navbarCollapse.classList.remove('show');
            }
        });

        // La resta del teu codi existent
        @auth
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
        @endauth
    });
</script>

@stack('scripts')

@if(session('success'))
    <x-alert type="success" message="{{ session('success') }}" />
@endif

@if(session('error'))
    <x-alert type="error" message="{{ session('error') }}" />
@endif
</body>
</html>
