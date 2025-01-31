<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $username = '620c2bbd795b1cd8f959f5c76e6a93ed';
        $key = '8a7c3ab8cac2b91ab85a6d06f63afd9f';

        // Gelen verilerin kontrolü
        if (!(isset($request->res) && isset($request->hash))) {
            return response("missing parameter", 400);
        }

        // Hash doğrulama
        $hash = hash_hmac('sha256', $request->res . $username, $key, false);
        if (strcmp($hash, $request->hash) !== 0) {
            return response("invalid hash", 400);
        }

        // Verilerin çözülmesi
        $json_result = base64_decode($request->res);
        $array_result = json_decode($json_result, true);

        // Gelen veriler
        $order_id = $array_result['orderid'];
        $status = $array_result['status']; // Ödeme durumu (ör. başarılı, başarısız)

        // Siparişi bul ve güncelle
        $order = Order::where('order_id', $order_id)->first();

        if (!$order) {
            return response("order not found", 404);
        }

        if ($order->status === 'başarılı') {
            return response("success"); // Sipariş zaten işlenmiş
        }

        if ($status === 'başarılı') {
            $order->update([
                'status' => 'başarılı',
            ]);

            // Kullanıcıya kontör/paket tanımlayın
            $order->paket->kontoradeti += 1; // Örnek işlem
            $order->paket->save();
        } else {
            $order->update([
                'status' => 'başarısız',
            ]);
        }

        return response("success");
    }
}
