<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basit Laravel Sayfası</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Utangaç sms</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Anasayfa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Hakkımızda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">İletişim</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1>UTANÇAG SMS e Hoş Geldiniz</h1>
        <p class="lead">Bu, basit bir statik Laravel Blade sayfasıdır.</p>
    </div>
</header>

<main class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Özellik 1</h5>
                    <p class="card-text">Laravel ile web geliştirme oldukça kolaydır.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Özellik 2</h5>
                    <p class="card-text">Statik sayfalar oluşturabilir veya dinamik içerik ekleyebilirsiniz.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Özellik 3</h5>
                    <p class="card-text">Laravel güçlü bir framework'tür ve öğrenmesi kolaydır.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="bg-light text-center py-3">
    <p>© 2025 Laravel App. Tüm Hakları Saklıdır.</p>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
