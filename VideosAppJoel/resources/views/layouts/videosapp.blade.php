<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplicació de Vídeos')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            margin: 0;
            min-height: 100vh; /* Assegura que el body ocupi almenys l'alçada de la finestra */
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
            flex: 1 0 auto; /* Fa que el contingut principal ocupi l'espai restant */
            padding-bottom: 70px; /* Espai per al footer fix */
        }
        .footer {
            flex-shrink: 0; /* Evita que el footer es redueixi */
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Usuaris</a></li> <!-- Afegit aquí -->
                    @can('manage videos')
                        <li class="nav-item"><a class="nav-link" href="{{ route('videos.manage.index') }}">Gestionar</a></li>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
