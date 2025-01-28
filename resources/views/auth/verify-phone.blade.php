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
            <div class="mb-3 d-flex">
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Telefon Numaranız" value="{{ old('phone') }}" required>
                <button type="button" class="btn btn-secondary ms-2" id="send-code">Kod Gönder</button>
            </div>

            <!-- Doğrulama Kodu -->
            <div class="mb-3">
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
        document.getElementById('send-code').addEventListener('click', function () {
            const phone = document.getElementById('phone').value;

            if (!phone || !/^[0-9]{10,15}$/.test(phone)) {
                alert('Lütfen geçerli bir telefon numarası girin.');
                return;
            }

            // CSRF Token alıyoruz
            const tokenElement = document.querySelector('meta[name="csrf-token"]');
            if (!tokenElement) {
                alert('CSRF token bulunamadı. Sayfayı yenileyin ve tekrar deneyin.');
                return;
            }

            const token = tokenElement.getAttribute('content');

            fetch('{{ route('phone.send-code') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify({ phone: phone }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Doğrulama kodu gönderildi.');
                    } else {
                        alert('Kod gönderilemedi: ' + (data.message || 'Bilinmeyen bir hata.'));
                    }
                })
                .catch(error => {
                    console.error('Hata:', error);
                    alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                });
        });
    </script>
@endsection
