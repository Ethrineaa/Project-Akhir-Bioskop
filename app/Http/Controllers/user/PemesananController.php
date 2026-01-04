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
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    public function index()
    {
        $pemesanans = Pemesanan::with(['jadwal.film', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.pemesanans.index', compact('pemesanans'));
    }

    /**
     * =========================
     * HALAMAN PILIH KURSI
     * =========================
     */
    public function pilihKursi(Jadwal $jadwal)
    {
        $kursi = Kursi::where('studio_id', $jadwal->studio_id)->get();

        $kursiTerpesan = DB::table('kursi_pemesanan')
            ->join('pemesanans', 'pemesanans.id', '=', 'kursi_pemesanan.pemesanan_id')
            ->join('pembayaran', 'pembayaran.pemesanan_id', '=', 'pemesanans.id')
            ->where('pemesanans.jadwal_id', $jadwal->id)
            ->whereIn('pembayaran.status', ['waiting', 'pending', 'paid'])
            ->pluck('kursi_pemesanan.kursi_id')
            ->toArray();

        return view('user.kursi.index', compact('jadwal', 'kursi', 'kursiTerpesan'));
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
