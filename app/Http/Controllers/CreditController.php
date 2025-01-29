<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CreditController extends Controller
{
    public function show()
    {
        $packages = Paket::all(); // Eloquent ile tüm paketler alınır
        return view('credits.show', compact('packages'));
    }

    public function process(Request $request)
    {
        if (Auth::user()->phone_verified == 0) {
            return redirect()->route('phone.verify.form')
                ->with('error', 'Lütfen telefon numaranızı doğrulayın.');
        }
        $request->validate([
            'paket_id' => 'required|exists:paketler,id',
            'payment_method' => 'required|in:credit_card,gsm',
        ]);

        // Seçilen paket bilgilerini alın
        $package = Paket::findOrFail($request->paket_id);

        // PayTR API bilgilerini doğrudan burada tanımlıyoruz
        $merchant_id = '535782';
        $merchant_key = 'PJqGuehf6a3bhcse';
        $merchant_salt = 'M7jaZmg5ZpzrcZw5';
        $success_url = route('credits.success');
        $fail_url = route('credits.fail');

        // Kullanıcı bilgileri ve ödeme miktarı
        $user_ip = request()->ip();
        $merchant_oid = uniqid();
        $payment_amount = $package->paketbedeli * 100; // TL'den kuruşa çevir
        $payment_type = $request->payment_method;

        // PayTR için gerekli veriler
        $post_data = [
            'merchant_id' => $merchant_id,
            'user_ip' => $user_ip,
            'merchant_oid' => $merchant_oid,
            'email' => auth()->user()->email,
            'payment_amount' => $payment_amount,
            'payment_type' => $payment_type === 'gsm' ? 'gsm' : 'card',
            'currency' => 'TL',
            'success_url' => $success_url,
            'fail_url' => $fail_url,
        ];

        // Hash oluşturma
        $hash_str = $merchant_id . $user_ip . $merchant_oid . $payment_amount . $success_url . $fail_url . $merchant_salt;
        $post_data['paytr_token'] = base64_encode(hash_hmac('sha256', $hash_str, $merchant_key, true));

        // PayTR'ye istek gönder
        $response = Http::post('https://www.paytr.com/odeme/api/get-token', $post_data);

        $result = $response->json();

        if ($result['status'] === 'success') {
            // Ödeme ekranına yönlendir
            return redirect()->away($result['token_url']);
        } else {
            return redirect()->route('credits.show')->with('error', 'Ödeme işlemi başlatılamadı: ' . $result['reason']);
        }
    }

    public function success(Request $request)
    {
        $packageId = $request->package_id; // Paket ID'yi alın
        $package = Paket::findOrFail($packageId);

        // Kullanıcının kredi tablosunu güncelle
        Credit::create([
            'user_id' => auth()->user()->id,
            'payment_method' => $request->payment_method,
            'status' => 'approved',
            'amount' => $package->kontoradeti,
        ]);

        return redirect()->route('credits.show')->with('success', 'Paket başarıyla satın alındı ve kontörler tanımlandı!');
    }

    public function fail()
    {
        return redirect()->route('credits.show')->with('error', 'Ödeme işlemi başarısız oldu.');
    }

}
