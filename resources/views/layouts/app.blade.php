<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Utangaç SMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Tüm sayfayı kaplamasını sağlayan yapı */
        html, body {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        main {
            flex-grow: 1; /* İçerik kısmının dinamik olarak genişlemesini sağlar */
        }

        /* Navbar */
        .custom-navbar {
            background-color: #FFD700; /* Tam sarı */
            padding: 1.1rem 10px;
        }

        .custom-navbar .navbar-brand {
            color: black !important;
            font-weight: bold;
        }

        .custom-navbar .nav-link {
            color: black !important;
            font-weight: bold;
            position: relative; /* Hover alt çizgiyi eklemek için */
        }

        /* Hover efekti: Alt çizgi kırmızı */
        .custom-navbar .nav-link::after {
            content: "";
            display: block;
            width: 100%;
            height: 2px;
            background-color: red;
            position: absolute;
            left: 0;
            bottom: -3px; /* Yazının hemen altına */
            transform: scaleX(0); /* Başlangıçta gizli */
            transition: transform 0.3s ease-in-out;
        }

        .custom-navbar .nav-link:hover::after {
            transform: scaleX(1); /* Hover yapıldığında çizgiyi göster */
        }

        /* Footer */
        footer {
            background-color: #FFD700; /* Navbar ile aynı renk */
            padding: 2rem 0;
            border-top: 1px solid #e4e4e4;
            margin-top: auto; /* Footer'ı sayfanın en altına iter */
        }

        footer ul {
            list-style-type: none;
            padding: 0;
        }

        footer ul li a {
            color: #343a40;
            text-decoration: none;
        }

        footer ul li a:hover {
            text-decoration: underline;
        }

        .payment-logos img {
            width: 270px;
            height: 50px;
            margin-right: 10px;
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
                    <a class="nav-link" href="{{ route('credits.show') }}">Paketler</a>
                </li>
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Kayıt Ol</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Giriş Yap</a>
                    </li>
                @endguest
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('inbox.show') }}">Gelen Kutusu</a>
                    </li>
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
                @endauth
            </ul>
        </div>
    </div>
</nav>


<!-- İçerik Alanı -->
<main>
    @yield('content')
</main>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-start">
                <ul>
                    <li><a href="{{ route('hakkimizda') }}">Hakkımızda</a></li>
                    <li><a href="{{ route('teslimat') }}">Teslimat ve İade Şartları</a></li>
                    <li><a href="{{ route('gizlilik') }}">Gizlilik Sözleşmesi</a></li>
                    <li><a href="{{ route('mesafe') }}">Mesafeli Satış Sözleşmesi</a></li>
                </ul>
            </div>

            <div class="col-md-6 text-end">
                <div class="payment-logos">
                    <img src="{{ asset('images/iyzico-logo.png') }}" alt="Iyzico">
                </div>
            </div>
        </div>
        <hr>
        <div class="text-center">
            <p class="mb-0">© 2025 Utangaç SMS. Tüm Hakları Saklıdır.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
