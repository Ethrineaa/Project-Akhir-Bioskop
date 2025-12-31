<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function pay($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        // set midtrans config
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // simpan pembayaran (pending)
        $pembayaran = Pembayaran::firstOrCreate(
            ['pemesanan_id' => $pemesanan->id],
            ['status' => 'pending']
        );

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $pemesanan->id . '-' . time(),
                'gross_amount' => $pemesanan->total_harga,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('user.pemesanan.payment', compact('snapToken', 'pemesanan'));
    }
}

