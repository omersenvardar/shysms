@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Gelen Mesajlar</h2>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="list-group">
            @foreach ($messages as $key => $message)
                <div class="list-group-item message-item" data-bs-toggle="collapse" data-bs-target="#message-{{ $key }}">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $message['sender'] ?? 'Gizli Gönderici' }}</strong>
                        <small>{{ $message['date'] ?? 'Bilinmeyen Tarih' }}</small>
                    </div>
                    <p class="mb-1">{{ $message['message'] ?? 'Mesaj içeriği yok.' }}</p>
                </div>
                <div class="collapse message-details" id="message-{{ $key }}">
                    <div>
                        <strong>Mesaj Detayları:</strong>
                        <p>Mesaj: {{ $message['message'] ?? 'Mesaj içeriği yok.' }}</p>
                        <p>Gönderici: {{ $message['sender'] ?? 'Gizli Gönderici' }}</p>
                        <p>Mesaj Tarihi: {{ $message['date'] ?? 'Bilinmeyen Tarih' }}</p>
                    </div>

                    <!-- Reply Form -->
                    <div class="reply-section mt-3">
                        <h5>Cevap Yaz</h5>
                        <form action="{{ route('inbox.reply') }}" method="POST">
                            @csrf
                            <input type="hidden" name="message_id" value="{{ $message['id'] }}">
                            <div class="mb-3">
                                <textarea class="form-control" name="reply_text" rows="3" placeholder="Cevabınızı buraya yazın..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Cevap Gönder</button>
                        </form>
                    </div>
                </div>
            @endforeach

            @if (count($messages) === 0)
                <p class="text-center mt-3">Hiç mesaj bulunamadı.</p>
            @endif
        </div>
    </div>

    <script>
        // Mesaj detaylarını ve cevap formunu göstermek için
        document.querySelectorAll('.message-item').forEach(function (item) {
            item.addEventListener('click', function () {
                const targetId = item.getAttribute('data-bs-target');
                const targetMessageDetails = document.querySelector(targetId);

                const isActive = targetMessageDetails.classList.contains('show');

                document.querySelectorAll('.message-details').forEach(function (messageDetail) {
                    messageDetail.classList.remove('show');
                });

                if (!isActive) {
                    targetMessageDetails.classList.add('show');
                }
            });
        });
    </script>
@endsection
