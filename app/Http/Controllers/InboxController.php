<?php

namespace App\Http\Controllers;

use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class InboxController extends Controller
{
    public function show()
    {
        if (Auth::user()->phone_verified == 0) {
            return redirect()->route('phone.verify.form')
                ->with('error', 'Lütfen telefon numaranızı doğrulayın.');
        }

        $apiKey = '2fedf9d2ba43f485dbcb751afb2702246222c764';
        $apiUrl = 'https://api.organikhaberlesme.com/sms/inbox/message';

        try {
            $response = Http::withHeaders([
                'X-Organik-Auth' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post($apiUrl, [
                'id' => 1139, // Gelen kutusunu filtrelemek için kullanılacak ID
            ]);

            $data = $response->json();

            if (isset($data['result']) && $data['result'] === true) {
                $messages = $data['data'];
                session(['messages' => $messages]); // Gelen mesajları oturuma kaydet
            } else {
                $messages = [];
                $error = $data['error']['message'] ?? 'Bilinmeyen bir hata oluştu.';
                return view('inbox', compact('messages'))->with('error', $error);
            }
        } catch (\Exception $e) {
            return view('inbox', ['messages' => []])->with('error', 'API bağlantısı sağlanamadı: ' . $e->getMessage());
        }

        return view('inbox', compact('messages'));
    }

    public function getKeywords()
    {
        if (Auth::user()->phone_verified == 0) {
            return redirect()->route('phone.verify.form')
                ->with('error', 'Lütfen telefon numaranızı doğrulayın.');
        }
        // API'den anahtar kelimeleri çek
        $response = Http::withHeaders([
            'X-Organik-Auth' => '2fedf9d2ba43f485dbcb751afb2702246222c764', // API Anahtarınız
        ])->get('https://api.organikhaberlesme.com/sms/inbox/keyword');

        if ($response->ok()) {
            $keywords = $response->json()['data'] ?? []; // Gelen verileri al
            return view('keywords.index', compact('keywords')); // Blade'e gönder
        } else {
            return back()->withErrors('Anahtar kelimeler alınamadı!');
        }
    }
    public function reply(Request $request, SmsService $smsService)
    {
        if (Auth::user()->phone_verified == 0) {
            return redirect()->route('phone.verify.form')
                ->with('error', 'Lütfen telefon numaranızı doğrulayın.');
        }
        // Form doğrulama
        $request->validate([
            'message_id' => 'required|integer',
            'reply_text' => 'required|max:1224',
        ]);

        // Gelen mesaj ID'si üzerinden gerekli bilgileri alın
        $messageId = $request->message_id;
        $originalMessage = collect(session('messages'))->firstWhere('id', $messageId); // Gelen mesajı oturumda veya veri tabanında bul

        if (!$originalMessage) {
            return redirect()->back()->with('error', 'Gelen mesaj bulunamadı.');
        }

        $recipient = $originalMessage['sender'] ?? null; // Gönderici numarası

        if (!$recipient) {
            return redirect()->back()->with('error', 'Gönderici numarası bulunamadı.');
        }

        // Gönderilecek veri
        $data = [
            'recipient' => $recipient,
            'message' => $request->reply_text,
        ];

        // SMS gönderme işlemi
        $response = $smsService->sendSms($data);

        if (isset($response['result']) && $response['result'] === true) {
            return redirect()->back()->with('success', 'Cevabınız başarıyla gönderildi!');
        } else {
            $errorMessage = $response['error']['message'] ?? 'Bilinmeyen bir hata oluştu.';
            return redirect()->back()->with('error', 'Cevap gönderilemedi: ' . $errorMessage);
        }
    }



}
