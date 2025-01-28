<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        $paketler = Paket::all();
        return view('paketler.index', compact('paketler'));
    }

    public function satinAl(Request $request)
    {
        $paket = Paket::findOrFail($request->paket_id);
        // Kullanıcıyı PayTR entegrasyonu için yönlendirin
        return redirect()->route('paytr.payment', ['paket_id' => $paket->id]);
    }
}
