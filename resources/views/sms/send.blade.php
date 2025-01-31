<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utangaç SMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<style>
    .custom-navbar {
        background-color: #FFD700;
        padding: 1.1rem 10px;
    }

    .custom-navbar .navbar-brand, .custom-navbar .nav-link {
        color: black !important;
        font-weight: bold;
    }

    .custom-navbar .navbar-toggler-icon {
        background-color: black;
    }

    .hidden {
        display: none;
    }

    .phone-input-container {
        position: relative;
    }

    .country-code {
        position: absolute;
        left: 10px;
        font-size: 1rem;
        color: #6c757d;
        background-color: #f8f9fa;
        border-right: 1px solid #ced4da;
        padding: 0.300rem 0.50rem;
        border-top-left-radius: 0.15rem;
        border-bottom-left-radius: 0.25rem;
    }

    .phone-input-container .form-control {
        padding-left: 60px;
    }
</style>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Utangaç SMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('nasil') }}">Nasıl Çalışır</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('credits.show') }}">Paketler</a></li>
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
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-3">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show auto-close-alert" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show auto-close-alert" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

<!-- Main Section -->
<div class="container mt-5">
    <div class="row">
        <!-- Form Section -->
        <div class="col-md-6">
            <h2 class="mb-4">İsimsiz bir kısa mesaj gönderin. Şimdi deneyin!</h2>

            <!-- Button Section -->
            <div class="mb-4">
                <button id="new-recipient-btn" class="btn btn-success">Yeni Alıcılara Mesaj Gönder</button>
                <button id="existing-recipient-btn" class="btn btn-warning">Kayıtlı Alıcılara Mesaj Gönder</button>
            </div>

            <!-- New Recipient Form -->
            <form id="new-recipient-form" action="{{ route('sms.send') }}" method="POST">
                @csrf
                <div class="mb-3 position-relative">
                    <label for="recipient" class="form-label">Alıcı Telefon Numarası</label>
                    <div class="phone-input-container">
                        <span class="country-code">+90</span>
                        <input
                            type="text"
                            class="form-control"
                            id="recipient"
                            name="recipient"
                            maxlength="10"
                            pattern="\d{10}"
                            placeholder="5555555555"
                            required
                        >
                    </div>
                    <small id="phone-help" class="form-text text-muted">
                        Telefon numarasını başında 0 olmadan 10 hane olarak girin.
                    </small>
                </div>
                <div class="mb-3">
                    <label for="nickname" class="form-label">Alıcı Nickname</label>
                    <input
                        type="text"
                        class="form-control"
                        id="nickname"
                        name="nickname"
                        maxlength="255"
                        placeholder="Alıcı nickname girin"
                        required
                    >
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Mesaj İçeriği</label>
                    <textarea
                        class="form-control"
                        id="message"
                        name="message"
                        rows="4"
                        maxlength="1224"
                        placeholder="Mesajınızı buraya yazın"
                        required
                    ></textarea>
                    <small id="credit-info" class="form-text text-muted mt-2">Mesajınız 0 kontör gerektiriyor.</small>
                </div>
                <button type="submit" class="btn btn-success w-100">Gönder</button>
            </form>

            <!-- Existing Recipient Form -->
            <form id="existing-recipient-form" action="{{ route('sms.send') }}" method="POST" class="hidden">
                @csrf
                <div class="mb-3">
                    <label for="recipient_id" class="form-label">Kayıtlı Alıcılar</label>
                    <select class="form-select" id="recipient_id" name="recipient_id" required>
                        <option value="">Alıcı Seçin</option>
                        @foreach ($recipients as $recipient)
                            <option value="{{ $recipient->recipient_phone }}">{{ $recipient->recipient_nickname }} - {{ $recipient->recipient_phone }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Mesaj İçeriği</label>
                    <textarea
                        class="form-control"
                        id="message"
                        name="message"
                        rows="4"
                        maxlength="1224"
                        placeholder="Mesajınızı buraya yazın"
                        required
                    ></textarea>
                    <small id="credit-info" class="form-text text-muted mt-2">Mesajınız 0 kontör gerektiriyor.</small>
                </div>
                <button type="submit" class="btn btn-warning w-100">Gönder</button>
            </form>
        </div>

        <!-- Preview Section -->
        <div class="col-md-6 text-center">
            <div class="mobile-device" style="position: relative; width: 360px; height: 660px; background: #e3f2fd; border-radius: 30px; margin: 0 auto;">
                <div class="device-camera" style="width: 50px; height: 5px; background: #ccc; border-radius: 10px; position: absolute; top: 15px; left: 50%; transform: translateX(-50%);"></div>
                <div class="device-screen-container" style="position: absolute; top: 50px; bottom: 20px; left: 20px; right: 20px; background: #fff; border-radius: 20px; padding: 15px; box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);">
                    <div class="text-bubble" style="background: #00bcd4; color: #fff; padding: 10px 15px; border-radius: 15px; max-width: 80%; margin-bottom: 10px;">
                        <p id="preview-message" style="margin: 0;">Hey! Bu gizli bir göndericiden bir metin mesajı. Kendiniz deneyin! 😄</p>
                    </div>
                    <span class="preview-info" style="font-size: 0.9rem; color: #666;">Bu SMS size www.utangacsms.com tarafından, utangaç bir üyemiz tarafından gönderilmiştir. Mesaja cevap yazarsanız kendisine iletilecektir.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="container mt-5">
        <div class="row text-center">
            <!-- %100 Anonim -->
            <div class="col-md-4">
                <i class="bi bi-question-circle mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                <h5>%100 anonim</h5>
                <p>Kime hep anonim olarak bir şey söylemek istediniz? Komşunuz, en iyi arkadaşınız, yoksa aşık olduğunuz kişi mi? İşte size fırsat! İşin güzel yanı, bu kişi mesajın kimden geldiğini asla bilmeyecek. Tabii siz kendinizi ifşa etmedikçe!</p>
            </div>
            <!-- Gizlilik -->
            <div class="col-md-4">
                <i class="bi bi-shield-lock mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                <h5>Gizlilik</h5>
                <p>Gizliliğiniz her şeyden önce gelir. Kısa mesaj göndermek için herhangi bir detay eklemenize gerek yoktur ve tüm bilgiler kısa bir süre sonra sistemimizden otomatik olarak kaldırılacaktır!</p>
            </div>
            <!-- Kolay -->
            <div class="col-md-4">
                <i class="bi bi-check-circle mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                <h5>Kolay</h5>
                <p>Çocuk oyuncağı! Göndericiyi, alıcıyı ve mesajı siz belirlersiniz. Gerisini biz hallederiz. Kayıt olmanıza gerek yok!</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const newRecipientBtn = document.getElementById("new-recipient-btn");
        const existingRecipientBtn = document.getElementById("existing-recipient-btn");
        const newRecipientForm = document.getElementById("new-recipient-form");
        const existingRecipientForm = document.getElementById("existing-recipient-form");

        // Default olarak yeni alıcı formunu göster
        newRecipientForm.classList.remove("hidden");
        existingRecipientForm.classList.add("hidden");

        // Yeni Alıcılar Butonu
        newRecipientBtn.addEventListener("click", function () {
            newRecipientForm.classList.remove("hidden");
            existingRecipientForm.classList.add("hidden");
        });

        // Kayıtlı Alıcılar Butonu
        existingRecipientBtn.addEventListener("click", function () {
            existingRecipientForm.classList.remove("hidden");
            newRecipientForm.classList.add("hidden");
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1.5 saniye sonra alert mesajlarını otomatik kapat
        setTimeout(function () {
            let alerts = document.querySelectorAll(".auto-close-alert");
            alerts.forEach(function (alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 1500);
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
