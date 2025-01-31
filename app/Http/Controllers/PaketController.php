<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaketController extends Controller
{
    public function index()
    {
        $paketler = Paket::all();
        return view('paketler.index', compact('paketler'));
    }

    public function buy(Request $request, $paketId)
    {
        $paket = Paket::findOrFail($paketId);

        // Siparişi oluştur
        $order = Order::create([
            'paket_id' => $paket->id,
            'user_id' => auth()->id(), // Giriş yapmış kullanıcı
            'order_id' => Str::uuid(), // Benzersiz sipariş ID
            'price' => $paket->paketbedeli,
            'currency' => 'TL',
            'status' => 'bekliyor',
        ]);

        // Ödeme sayfasına yönlendirme
        $paymentUrl = "https://odeme-sistemi.com/checkout?order_id={$order->order_id}";
        return redirect($paymentUrl);
    }
}
