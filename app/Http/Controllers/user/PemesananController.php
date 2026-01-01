<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\Pemesanan;
use App\Models\Pembayaran;

use Midtrans\Snap;
use Midtrans\Config;

class PemesananController extends Controller
{
    // =============================
    // HALAMAN PILIH KURSI
    // =============================
    public function pilihKursi($jadwal_id)
    {
        $jadwal = Jadwal::with('film', 'studio')->findOrFail($jadwal_id);

        $kursi = Kursi::where('studio_id', $jadwal->studio_id)
            ->orderBy('nomor_kursi')
            ->get();

        return view('user.kursi.index', compact('jadwal', 'kursi'));
    }

    // =============================
    // SIMPAN PEMESANAN
    // =============================
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'seats' => 'required|string',
        ]);

        $seats = explode(',', $request->seats);
        $jumlahTiket = count($seats);

        if ($jumlahTiket < 1) {
            return back()->withErrors(['seats' => 'Pilih minimal satu kursi']);
        }

        $jadwal = Jadwal::with('film')->findOrFail($request->jadwal_id);

        // validasi kursi
        $validSeatCount = Kursi::where('studio_id', $jadwal->studio_id)
            ->whereIn('nomor_kursi', $seats)
            ->count();

        if ($validSeatCount !== $jumlahTiket) {
            return back()->withErrors(['seats' => 'Kursi tidak valid']);
        }

        $totalHarga = $jumlahTiket * $jadwal->film->harga;

        $pemesanan = DB::transaction(function () use ($jadwal, $jumlahTiket, $totalHarga) {
            $pemesanan = Pemesanan::create([
                'jadwal_id' => $jadwal->id,
                'user_id' => Auth::id(),
                'jumlah_tiket' => $jumlahTiket,
                'total_harga' => $totalHarga,
            ]);

            Pembayaran::create([
                'pemesanan_id' => $pemesanan->id,
                'status' => 'pending',
            ]);

            return $pemesanan;
        });

        // ➡️ HALAMAN PAYMENT
        return redirect()->route('user.pemesanan.payment', $pemesanan->id);
    }

    // =============================
    // RIWAYAT PEMESANAN
    // =============================
    public function index()
    {
        $pemesanan = Pemesanan::with('jadwal.film')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.pemesanan.index', compact('pemesanan'));
    }

    // =============================
    // DETAIL PEMESANAN
    // =============================
    public function show($id)
    {
        $pemesanan = Pemesanan::with(['jadwal.film', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.pemesanan.show', compact('pemesanan'));
    }

    // =============================
    // HALAMAN PEMBAYARAN + MIDTRANS
    // =============================
    public function payment($id)
    {
        $pemesanan = Pemesanan::with(['jadwal.film', 'jadwal.studio'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // MIDTRANS CONFIG
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $pemesanan->id . '-' . time(),
                'gross_amount' => $pemesanan->total_harga + 4000,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name ?? 'Customer',
                'email' => Auth::user()->email ?? 'customer@mail.com',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('user.pemesanan.payment', compact('pemesanan', 'snapToken'));
    }
}
