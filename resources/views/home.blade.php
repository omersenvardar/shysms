@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row align-items-center">
            <!-- Resim -->
            <div class="col-md-6 text-center">
                <img src="{{ asset('images/home.jpeg') }}" alt="Mesajlaşma Görseli" class="img-fluid rounded shadow" style="width: 600px; height: 400px;">
            </div>
            <!-- Metin -->
            <div class="col-md-6 text-center">
                <h1 class="mb-4" style="font-size: 2.5rem; font-weight: bold;">
                    Utangaç SMS ile GİZLİ Mesaj Gönder.
                    <span style="">Hemen Ücretsiz Üye</span> Olarak Deneyin
                </h1>
                <p class="lead" style="font-size: 1.8rem; color: red; font-weight: bolder ">Üye olan herkese 200 TL değerinde mesajlaşma HEDİYE!!!</p>
                <a href="{{ route('sms.form') }}" class="btn btn-danger btn-lg mt-4" style="color: white; font-weight: bold;">
                    Mesajlaşmaya Hemen Başla
                </a>
            </div>
        </div>
    </div>

    <!-- Bilgilendirme Kısmı -->
    <div class="container mt-5">
        <div class="row text-center">
            <div class="col-md-12">
                <h2>Sadece bir kısa mesaj ama anonim</h2>
                <p class="text-muted">
                    Herkesin aklından geçmiştir. Seçtiğiniz bir kişiye, onun siz olduğunuzu bilmeden mesaj gönderebilmek.
                    Örneğin, çok hoşlandığınız yakışıklı yeni sınıf arkadaşınıza. Ya da aile yemeğinde şaka yapmak için kuzeninize.
                    Sonsuz olasılıklar Utangaç SMS ile mümkün! İlham mı arıyorsunuz?
                </p>
            </div>
        </div>

        <div class="row text-center mt-4">
            <div class="col-md-4">
                <i class="fas fa-comments fa-4x text-primary mb-3"></i>
                <h5>Mesajınızı yazın</h5>
                <p>Herhangi birine isminizi eklemeden bir mesaj gönderin. Kendi tarzınızı yaratın.</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-paper-plane fa-4x text-success mb-3"></i>
                <h5>Kısa mesaj gönder</h5>
                <p>Güzel bir mesaj hazırlayın ve hemen paylaşın. Hızlı ve güvenli!</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-user-secret fa-4x text-danger mb-3"></i>
                <h5>Anonim kalın</h5>
                <p>Kendinizi belli etmeyin. Gizliliğiniz bizim önceliğimizdir.</p>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-4 text-center">
                <i class="fas fa-user-secret fa-3x text-info mb-3"></i>
                <h5>%100 Anonim</h5>
                <p>
                    Kime hep anonim olarak bir şey söylemek istediniz? Komşunuz, en iyi arkadaşınız, yoksa aşık olduğunuz kişi mi?
                    İşin güzel yanı, bu kişi mesajın kimden geldiğini asla bilemeyecek. Tabii siz kendinizi ifşa etmedikçe!
                </p>
            </div>
            <div class="col-md-4 text-center">
                <i class="fas fa-hand-pointer fa-3x text-success mb-3"></i>
                <h5>Gizlilik</h5>
                <p>
                    Gizliliğiniz her şeyden önce gelir. Kısa mesaj göndermek için herhangi bir detay eklemenize gerek yoktur ve
                    tüm bilgiler kısa bir süre sonra sistemimizden otomatik olarak kaldırılacaktır!
                </p>
            </div>
            <div class="col-md-4 text-center">
                <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                <h5>Kolay</h5>
                <p>
                    Çocuk oyuncağı! Göndericiyi, alıcıyı ve mesajı siz belirlersiniz. Gerisini biz hallederiz.
                    Kayıt olmanıza gerek yok!
                </p>
            </div>
        </div>
    </div>
@endsection
