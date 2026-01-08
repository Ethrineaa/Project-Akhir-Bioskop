<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;
class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('MIDTRANS CALLBACK RECEIVED', $request->all());

        // =========================
        // VALIDASI SIGNATURE
        // =========================
        $serverKey = config('midtrans.server_key');
        $signatureKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($signatureKey !== $request->signature_key) {
            Log::warning('MIDTRANS INVALID SIGNATURE', ['order_id' => $request->order_id]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // =========================
        // AMBIL PEMESANAN ID
        // order_id = ORDER-12
        // =========================
        $pemesananId = (int) str_replace('ORDER-', '', $request->order_id);

        $pembayaran = Pembayaran::where('pemesanan_id', $pemesananId)->first();

        if (!$pembayaran) {
            Log::warning('MIDTRANS PEMBAYARAN NOT FOUND', ['pemesanan_id' => $pemesananId]);
            return response()->json(['message' => 'Pembayaran not found'], 404);
        }

        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status ?? null;

        // =========================
        // LOGIC STATUS
        // =========================
        if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
            $pembayaran->update(['status' => 'paid']);
            Log::info('MIDTRANS PAYMENT SUCCESS', ['pemesanan_id' => $pemesananId, 'status' => 'paid']);
        } elseif (in_array($transactionStatus, ['cancel', 'expire', 'deny'])) {
            // OPTIONAL: biarkan waiting atau pending
            $pembayaran->update(['status' => 'waiting']);
            Log::info('MIDTRANS PAYMENT FAILED/CANCELLED', ['pemesanan_id' => $pemesananId, 'status' => 'waiting']);
        }

        return response()->json(['message' => 'OK']);
    }
}
