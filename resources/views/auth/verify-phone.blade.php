@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Telefon Doğrulama</h2>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('phone.verify') }}" method="POST">
            @csrf

            <!-- Telefon Numarası -->
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text">+90</span>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Telefon Numaranız" value="{{ old('phone') }}" maxlength="10" required>
                </div>
                <small class="form-text text-muted">Telefon numarasını başında 0 olmadan ve 10 hane olarak girin.</small>
            </div>

            <button type="button" class="btn btn-secondary mb-3" id="send-code" disabled>Kod Gönder</button>

            <!-- Doğrulama Kodu -->
            <div class="mb-3">
                <label for="verification_code" class="form-label">Doğrulama Kodu</label>
                <input type="text" class="form-control" id="verification_code" name="verification_code" placeholder="Doğrulama Kodu" required>
            </div>

            <!-- KVKK Onayı -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="kvkk_agreement" name="kvkk_agreement" required>
                <label class="form-check-label" for="kvkk_agreement">
                    KVKK Sözleşmesini okudum ve kabul ediyorum.
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Doğrula</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sendCodeButton = document.getElementById('send-code');
            const phoneInput = document.getElementById('phone');
            let countdown;

            // İlk yüklemede butonu kontrol et
            checkPhoneInput();

            // Telefon numarası değiştikçe buton durumunu kontrol et
            phoneInput.addEventListener('input', checkPhoneInput);

            function checkPhoneInput() {
                const phone = phoneInput.value;
                if (phone.length === 10 && /^[0-9]+$/.test(phone)) {
                    sendCodeButton.disabled = false;
                } else {
                    sendCodeButton.disabled = true;
                }
            }

            sendCodeButton.addEventListener('click', function () {
                const phone = phoneInput.value;

                // Telefon numarasının geçerli olup olmadığını kontrol edin
                if (!phone || phone.length !== 10 || !/^[0-9]+$/.test(phone)) {
                    alert('Lütfen 10 haneli geçerli bir telefon numarası girin.');
                    return;
                }

                // CSRF Token alıyoruz
                const tokenElement = document.querySelector('meta[name="csrf-token"]');
                const token = tokenElement ? tokenElement.getAttribute('content') : null;

                if (!token) {
                    alert('CSRF token bulunamadı. Sayfayı yenileyin ve tekrar deneyin.');
                    return;
                }

                // Doğrulama kodu gönderme isteği
                fetch('{{ route('phone.send-code') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({ phone: '90' + phone }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Doğrulama kodu gönderildi.');
                            startCountdown(); // Geri sayım başlat
                        } else {
                            alert('Kod gönderilemedi: ' + (data.message || 'Bilinmeyen bir hata.'));
                        }
                    })
                    .catch(error => {
                        console.error('Hata:', error);
                        alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                    });
            });

            function startCountdown() {
                let seconds = 60;
                sendCodeButton.disabled = true;
                updateButtonText(seconds);

                countdown = setInterval(() => {
                    seconds--;
                    updateButtonText(seconds);

                    if (seconds <= 0) {
                        clearInterval(countdown);
                        sendCodeButton.disabled = false;
                        sendCodeButton.textContent = 'Kod Gönder';
                    }
                }, 1000);
            }

            function updateButtonText(seconds) {
                sendCodeButton.textContent = `Tekrar Gönder (${seconds})`;
            }
        });
    </script>
@endsection
