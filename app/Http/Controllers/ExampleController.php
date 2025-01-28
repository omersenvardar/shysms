<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function method()
    {
        return view('example'); // 'example' adında bir Blade dosyasını döndürür
    }
}
