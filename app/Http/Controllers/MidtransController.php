<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
     public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $signature = hash('sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($signature !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // ORDER-12 → 12
        $pemesananId = str_replace('ORDER-', '', $request->order_id);

        $pemesanan = Pemesanan::findOrFail($pemesananId);
        $pembayaran = Pembayaran::where('pemesanan_id', $pemesanan->id)->first();

        switch ($request->transaction_status) {
            case 'capture':
            case 'settlement':
                $status = 'paid';
                break;

            case 'pending':
                $status = 'pending';
                break;

            case 'expire':
            case 'cancel':
            case 'deny':
                $status = 'waiting';
                break;

            default:
                $status = 'waiting';
        }

        $pemesanan->update(['status' => $status]);
        $pembayaran->update(['status' => $status]);

        return response()->json(['message' => 'OK']);
    }
}
