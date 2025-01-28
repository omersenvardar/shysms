<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;
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
        $request->validate([
            'paket_id' => 'required|exists:paketler,id', // Geçerli bir paket ID olmalı
            'payment_method' => 'required|in:gsm,credit_card',
        ]);

        // Paketin bilgilerini al
        $paket = \App\Models\Paket::findOrFail($request->paket_id);

        // PayTR API bilgileri
        $merchant_id = env('PAYTR_MERCHANT_ID');
        $merchant_key = env('PAYTR_MERCHANT_KEY');
        $merchant_salt = env('PAYTR_MERCHANT_SALT');
        $success_url = route('credits.success');
        $fail_url = route('credits.fail');

        $paymentMethod = $request->payment_method;

        // PayTR API'ye gönderilecek bilgiler
        $user_ip = request()->ip();
        $merchant_oid = uniqid(); // Benzersiz işlem numarası

        $post_data = [
            'merchant_id' => $merchant_id,
            'user_ip' => $user_ip,
            'merchant_oid' => $merchant_oid,
            'email' => auth()->user()->email, // Kullanıcının e-posta adresi
            'payment_amount' => $paket->paketbedeli * 100, // PayTR kuruş bazlı çalışır
            'payment_type' => $paymentMethod === 'gsm' ? 'gsm' : 'card',
            'currency' => 'TL',
            'success_url' => $success_url,
            'fail_url' => $fail_url,
        ];

        // Hash oluşturma
        $hash_str = $merchant_id . $user_ip . $merchant_oid . $post_data['payment_amount'] . $success_url . $fail_url . $merchant_salt;
        $post_data['paytr_token'] = base64_encode(hash_hmac('sha256', $hash_str, $merchant_key, true));

        // PayTR'ye isteği gönder
        $response = Http::post('https://www.paytr.com/odeme/api/get-token', $post_data);

        $result = $response->json();

        if ($result['status'] === 'success') {
            // Kullanıcı ödemeyi yaparken credits tablosuna işlem başlatma kaydı
            \App\Models\Credit::create([
                'user_id' => auth()->id(),
                'payment_method' => $paymentMethod,
                'status' => 'pending', // Başlangıçta beklemede
                'amount' => $paket->kontoradeti, // Kontör adedi
            ]);

            // Ödeme sayfasına yönlendir
            return redirect()->away($result['token_url']);
        } else {
            return redirect()->route('credits.show')->with('error', 'Ödeme işlemi başlatılamadı: ' . $result['reason']);
        }
    }

    public function success(Request $request)
    {
        // Ödeme başarılı, ilgili krediyi güncelle
        $credit = \App\Models\Credit::where('user_id', auth()->id())
            ->where('status', 'pending') // Bekleyen işlem
            ->latest()
            ->first();

        if ($credit) {
            $credit->update(['status' => 'approved']);

            // Kullanıcıya kontör ekle
            auth()->user()->increment('balance', $credit->amount);
        }

        return view('credits.success')->with('success', 'Paket başarıyla satın alındı ve kontörleriniz eklendi!');
    }


    public function fail()
    {
        return view('credits.fail');
    }
}
