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
        return view('sms.send');
    }

    public function send(Request $request, SmsService $smsService)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'SMS göndermek için giriş yapmalısınız.');
        }
        // Form verilerini doğrula
        $request->validate([
            'recipient' => 'required|regex:/^[0-9]{10,15}$/', // 10-15 haneli numara kontrolü
            'message' => 'required|max:1224',                // Mesaj sınırı 1224 karakter
        ]);

        // Kullanıcıdan gelen veriler
        $data = [
            'recipient' => $request->recipient,
            'message' => $request->message,
        ];

        // SmsService aracılığıyla API'ye isteği gönder
        $response = $smsService->sendSms($data);

        // API yanıtını kontrol et
        if (isset($response['result']) && $response['result'] === true) {
            return redirect()->back()->with('success', 'Mesaj başarıyla gönderildi!');
        } else {
            $errorMessage = $response['error']['message'] ?? 'Bilinmeyen bir hata oluştu.';
            return redirect()->back()->with('error', 'Mesaj gönderilemedi: ' . $errorMessage);
        }
    }
}
