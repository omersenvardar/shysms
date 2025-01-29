<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function showForm()
    {
        if (Auth::user()->phone_verified == 0) {
            return redirect()->route('phone.verify.form')
                ->with('error', 'Lütfen telefon numaranızı doğrulayın.');
        }
        return view('sms.send');
    }

    public function send(Request $request, SmsService $smsService)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'SMS göndermek için giriş yapmalısınız.');
        }
        if (Auth::user()->phone_verified == 0) {
            return redirect()->route('phone.verify.form')
                ->with('error', 'Lütfen telefon numaranızı doğrulayın.');
        }

        // Form verilerini doğrula
        $request->validate([
            'recipient' => 'required|regex:/^[0-9]{10,15}$/', // 10-15 haneli numara kontrolü
            'message' => 'required|max:1224',                // Mesaj sınırı 1224 karakter
        ]);

        // Kullanıcı kontör kontrolü
        $user = Auth::user();
        $messageLength = strlen($request->message);
        $requiredCredits = ceil($messageLength / 140); // Her 140 karakter için 1 kontör

        // Kullanıcının yeterli kontörü yoksa
        $userCredits = \App\Models\Credit::where('user_id', $user->id)->where('status', 'approved')->sum('amount');
        if ($userCredits < $requiredCredits) {
            return redirect()->route('credits.show')->with('error', 'Yetersiz kontör! Lütfen kontör satın alın.');
        }

        // SMS Gönderme işlemi
        $data = [
            'recipient' => $request->recipient,
            'message' => $request->message,
        ];

        $response = $smsService->sendSms($data);

        // API yanıtını kontrol et
        if (isset($response['result']) && $response['result'] === true) {
            // Kullanıcının kontörünü azalt
            \App\Models\Credit::create([
                'user_id' => $user->id,
                'payment_method' => 'sms_sent', // SMS gönderimi için harcanan kontör
                'status' => 'approved',
                'amount' => -$requiredCredits, // Harcanan kontör negatif olarak kaydedilir
            ]);

            return redirect()->back()->with('success', 'Mesaj başarıyla gönderildi!');
        } else {
            $errorMessage = $response['error']['message'] ?? 'Bilinmeyen bir hata oluştu.';
            return redirect()->back()->with('error', 'Mesaj gönderilemedi: ' . $errorMessage);
        }
    }

}
