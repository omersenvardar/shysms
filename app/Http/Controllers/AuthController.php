<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nickname' => 'required|max:255',
            'password' => 'required|min:1',
        ]);

        $user = User::create([
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_verified' => false, // Varsayılan olarak doğrulama yapılmadı
            'kvkk_accepted' => false, // Varsayılan olarak KVKK onayı yapılmadı
            'name' => $request->nickname, // Name alanını nickname ile dolduruyoruz
        ]);

        Auth::login($user);

        return redirect()->route('phone.verify.form');
    }

    public function showPhoneVerifyForm()
    {
        return view('auth.verify-phone');
    }
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|regex:/^[0-9]{10,15}$/',
        ]);

        $verificationCode = rand(100000, 999999); // 6 haneli rastgele kod

        // Send SMS
        $smsService = new \App\Services\SmsService();
        $response = $smsService->sendSms([
            'recipient' => $request->phone,
            'message' => 'Doğrulama kodunuz: ' . $verificationCode,
        ]);

        if (isset($response['result']) && $response['result'] === true) {
            // Kod oturuma kaydedilir
            session(['verification_code' => $verificationCode, 'verification_phone' => $request->phone]);
            return back()->with('success', 'Doğrulama kodu başarıyla gönderildi.');
        } else {
            return back()->with('error', 'Doğrulama kodu gönderilemedi.');
        }
    }

    public function verifyPhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|regex:/^[0-9]{10,15}$/',
            'verification_code' => 'required|digits:6',
            'kvkk_agreement' => 'accepted', // KVKK onayı zorunlu
        ]);

        // Doğrulama kodunu kontrol et
        if (
            session('verification_code') == $request->verification_code &&
            session('verification_phone') == $request->phone
        ) {
            $user = Auth::user();
            $user->update([
                'phone' => $request->phone,
                'phone_verified' => true,
                'kvkk_accepted' => true,
            ]);

            // Oturumu temizle
            session()->forget(['verification_code', 'verification_phone']);

            return redirect('/sms/send')->with('success', 'Telefon numarası başarıyla doğrulandı ve KVKK sözleşmesi onaylandı!');
        }

        return back()->with('error', 'Doğrulama kodu yanlış veya süresi dolmuş.');
    }


}
