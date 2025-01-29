<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/sms/send'; // Başarılı giriş sonrası yönlendirme

    // Login için email yerine nickname kullanımı
    public function username()
    {
        return 'nickname';
    }

    // Doğrulama mantığı
    protected function credentials(Request $request)
    {
        return [
            'nickname' => $request->get('nickname'),
            'password' => $request->get('password'),
        ];
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
