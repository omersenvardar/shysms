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

        // Kullanıcıya ait kayıtlı alıcıları al
        $recipients = \App\Models\Recipient::where('user_id', Auth::id())->get();

        // View'e alıcıları gönder
        return view('sms.send', compact('recipients'));
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

        // Yeni alıcı mı, kayıtlı alıcı mı kontrol et
        $isNewRecipient = $request->has('recipient'); // Yeni alıcı kısmı mı?
        $recipientPhone = $isNewRecipient ? $request->recipient : $request->recipient_id;

        // Telefon numarasını başında 90 olacak şekilde düzenle
        if (!str_starts_with($recipientPhone, '90')) {
            $recipientPhone = "90" . $recipientPhone;
        }

        // Form verilerini doğrula
        $request->validate([
            'message' => 'required|max:1224',
            'nickname' => 'required_if:is_new,true|max:255', // Yeni alıcı için nickname gerekli
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

        $nickname = Auth::user()->nickname;

        // Mesajı formatla
        $formattedMessage = "{$nickname}: {$request->message}";

        // SMS Gönderme işlemi
        $data = [
            'recipient' => $recipientPhone,
            'message' => $formattedMessage,
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
