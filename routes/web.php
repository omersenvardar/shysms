<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CreditController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InboxController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SmsController;

// Anasayfayı /sms/send rotasına yönlendir
Route::get('/', function () {
    return view('home');
});
// /sms/send rotasına erişim herkese açık
Route::get('/sms/send', [SmsController::class, 'showForm'])->name('sms.form');
Route::post('/sms/send', [SmsController::class, 'send'])->name('sms.send');

// Logout işlemi sonrası /sms/send sayfasına yönlendir
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/sms/send');
})->name('logout');

// Oturum açma işlemleri
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);

// Login gerektiren rotalar
Route::middleware(['auth'])->group(function () {
    // Diğer SMS rotaları
    Route::post('/credits/process', [CreditController::class, 'process'])->name('credits.process');
    Route::get('/credits/success', [CreditController::class, 'success'])->name('credits.success');
    Route::get('/credits/fail', [CreditController::class, 'fail'])->name('credits.fail');

    // Gelen kutusu
    Route::get('/inbox', [InboxController::class, 'show'])->name('inbox.show');
    Route::get('/keywords', [InboxController::class, 'getKeywords'])->name('keywords.show');
    Route::post('/inbox/reply', [InboxController::class, 'reply'])->name('inbox.reply');

    // Kayıt ve telefon doğrulama

    Route::get('/phone/verify', [AuthController::class, 'showPhoneVerifyForm'])->name('phone.verify.form');
    Route::post('/phone/verify', [AuthController::class, 'verifyPhone'])->name('phone.verify');
    Route::post('/phone/send-code', [AuthController::class, 'sendVerificationCode'])->name('phone.send-code');

    // KVKK sayfası
    Route::get('/kvkk', function () {
        return view('kvkk');
    })->name('kvkk');

    Route::get('/credits', [CreditController::class, 'show'])->name('credits.show');
    Route::post('/credits/process', [CreditController::class, 'process'])->name('credits.process');
    Route::get('/credits/success', [CreditController::class, 'success'])->name('credits.success');
    Route::get('/credits/fail', [CreditController::class, 'fail'])->name('credits.fail');

});
    Route::get('/credits', [CreditController::class, 'show'])->name('credits.show');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/nasil', function () {
    return view('nasil');
    })->name('nasil');
