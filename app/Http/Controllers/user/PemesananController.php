<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{
    /**
     * =========================
     * HALAMAN PILIH KURSI
     * =========================
     */
    public function pilihKursi(Jadwal $jadwal)
    {
        // ✅ AMBIL SEMUA KURSI SESUAI STUDIO
        $kursi = Kursi::where('studio_id', $jadwal->studio_id)->get();

        // ✅ KURSI YANG SUDAH DIBAYAR
        $kursiTerpesan = Pembayaran::where('status', 'paid')
            ->whereHas('pemesanan', function ($q) use ($jadwal) {
                $q->where('jadwal_id', $jadwal->id);
            })
            ->pluck('pemesanan_id')
            ->toArray();

        return view('user.kursi.index', compact(
            'jadwal',
            'kursi',
            'kursiTerpesan'
        ));
    }

    /**
     * =========================
     * CHECKOUT + MIDTRANS
     * =========================
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'seats' => 'required|array|min:1',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        $jumlahTiket = count($request->seats);
        $totalHarga = $jumlahTiket * $jadwal->film->harga;

        $pemesanan = Pemesanan::create([
            'jadwal_id' => $jadwal->id,
            'user_id' => Auth::id(),
            'jumlah_tiket' => $jumlahTiket,
            'total_harga' => $totalHarga,
        ]);

        $pembayaran = Pembayaran::create([
            'pemesanan_id' => $pemesanan->id,
            'status' => 'waiting',
        ]);

        // MIDTRANS
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $snapToken = Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => 'ORDER-' . $pembayaran->id,
                'gross_amount' => $totalHarga,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
            ],
        ]);

        return response()->json([
            'snap_token' => $snapToken,
        ]);
    }
}
