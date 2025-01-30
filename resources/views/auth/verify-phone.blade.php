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
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Telefon Numaranız"
                           value="{{ old('phone') }}" maxlength="10" required>
                </div>
                <small class="form-text text-muted">Telefon numarasını başında 0 olmadan ve 10 hane olarak girin.</small>
            </div>

            <button type="button" class="btn btn-secondary mb-3" id="send-code" disabled>Kod Gönder</button>

            <!-- Doğrulama Kodu -->
            <div class="mb-3">
                <label for="verification_code" class="form-label">Doğrulama Kodu</label>
                <input type="text" class="form-control" id="verification_code" name="verification_code"
                       placeholder="Doğrulama Kodu" required>
            </div>

            <!-- Sözleşme -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="kvkk_agreement" name="kvkk_agreement" required>
                <label class="form-check-label" for="kvkk_agreement">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal"
                       style="text-decoration: underline; color: blue;">Site Kullanım Sözleşmesini</a> okudum ve kabul
                    ediyorum.
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Doğrula</button>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Site Kullanım Sözleşmesi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body" style="max-height: 700px; overflow-y: auto;">
                    <p>
                        Lütfen sitemizi kullanmadan önce bu <span class="highlight">site kullanım şartlarını</span> dikkatlice okuyunuz.
                        Bu siteyi kullanan tüm kullanıcılarımız aşağıdaki şartları kabul etmiş sayılacaktır.
                    </p>

                    <h4>1. Sorumluluklar</h4>
                    <ul>
                        <li>Firma, fiyatlar ve sunulan ürün ve hizmetler üzerinde değişiklik yapma hakkını her zaman saklı tutar.</li>
                        <li>Firma, teknik arızalar dışında kullanıcıya hizmet sağlanacağını kabul ve taahhüt eder.</li>
                        <li>Kullanıcı, siteye zarar verecek faaliyetlerden kaçınacağını kabul eder.</li>
                        <li>Kullanıcı, siteyi genel ahlaka ve kanunlara uygun şekilde kullanmayı taahhüt eder.</li>
                        <li>Kullanıcıların üçüncü şahıslarla ilişkileri kendi sorumluluğundadır.</li>
                    </ul>

                    <h4>2. Fikri Mülkiyet Hakları</h4>
                    <p>Siteye ait tüm içerik ve materyaller firma mülkiyetindedir. İzinsiz kopyalama, çoğaltma ve dağıtma yasaktır.</p>

                    <h4>3. Gizli Bilgi</h4>
                    <ul>
                        <li>Firma, kullanıcı bilgilerini izinsiz paylaşmayacağını taahhüt eder.</li>
                        <li>Resmi makamların talebi halinde kullanıcı bilgileri paylaşılabilir.</li>
                    </ul>

                    <h4>4. Kayıt ve Güvenlik</h4>
                    <p>Kullanıcı, şifre ve hesap güvenliğinden sorumludur. Şifre ihlallerinden kaynaklanabilecek zararlardan firma sorumlu tutulamaz.</p>

                    <h4>5. Mücbir Sebep</h4>
                    <p>Mücbir sebep durumlarında tarafların yükümlülükleri askıya alınır.</p>

                    <h4>6. Sözleşmede Yapılacak Değişiklikler</h4>
                    <p>Firma, bu sözleşme hükümlerinde değişiklik yapma hakkını saklı tutar. Değişiklikler yayınlandığı tarihte yürürlüğe girer.</p>

                    <h4>7. Ücretler</h4>
                    <p>Mesaj ücretleri alınan paketlere göre değişmektedir. Site kullanımına dair ücretlendirme detayları kullanıcıya önceden bildirilir.</p>

                    <h4>8. Uyuşmazlıkların Çözümü</h4>
                    <p>Tüm uyuşmazlıklar için Ankara Mahkemeleri yetkilidir.</p>

                    <div class="footer-note">
                        © 2025 MGORE GAYRİMENKUL HİZMETLERİ LTD. ŞTİ. Tüm Hakları Saklıdır.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="modalCloseButton">Kapat</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const kvkkCheckbox = document.getElementById('kvkk_agreement');
            const modalCloseButton = document.getElementById('modalCloseButton');
            const termsModal = document.getElementById('termsModal');

            // Modal kapandığında checkbox aktif hale gelsin
            termsModal.addEventListener('hidden.bs.modal', function () {
                kvkkCheckbox.disabled = false;
            });

            const sendCodeButton = document.getElementById('send-code');
            const phoneInput = document.getElementById('phone');

            function checkPhoneInput() {
                const phone = phoneInput.value;
                sendCodeButton.disabled = !(phone.length === 10 && /^[0-9]+$/.test(phone));
            }

            phoneInput.addEventListener('input', checkPhoneInput);

            sendCodeButton.addEventListener('click', function() {
                const phone = phoneInput.value;

                fetch('{{ route('phone.send-code') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ phone: '90' + phone }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Doğrulama kodu gönderildi.');
                        } else {
                            alert('Kod gönderilemedi: ' + (data.message || 'Bilinmeyen bir hata.'));
                        }
                    });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            sendCodeButton.addEventListener('click', function() {
                const phone = phoneInput.value;

                if (!phone || phone.length !== 10 || !/^[0-9]+$/.test(phone)) {
                    alert('Lütfen 10 haneli geçerli bir telefon numarası girin.');
                    return;
                }

                const tokenElement = document.querySelector('meta[name="csrf-token"]');
                const token = tokenElement ? tokenElement.getAttribute('content') : null;

                if (!token) {
                    alert('CSRF token bulunamadı. Sayfayı yenileyin ve tekrar deneyin.');
                    return;
                }

                fetch('{{ route('phone.send-code') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({
                        phone: '90' + phone
                    }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Doğrulama kodu gönderildi.');
                            startCountdown();
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
