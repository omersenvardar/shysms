<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Utangaç SMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .custom-navbar {
            background-color: #FFD700; /* Tam sarı */
            padding: 1.1rem 10px; /* Genişlik ve yükseklik artışı için */
        }

        .custom-navbar .navbar-brand {
            color: black !important;
            font-weight: bold;
        }

        .custom-navbar .nav-link {
            color: black !important;
            font-weight: bold;
        }

        .custom-navbar .navbar-toggler-icon {
            background-color: black;
        }

        .custom-navbar .nav-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Utangaç SMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('nasil') }}">Nasıl Çalışır</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('inbox.show') }}">Gelen Kutusu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('credits.show') }}">Paketler</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Kayıt Ol</a>
                </li>
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }} ({{ floor($totalCredits) }} Kontör)
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Çıkış Yap</a>
                            </li>
                        </ul>
                    </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Giriş Yap</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
