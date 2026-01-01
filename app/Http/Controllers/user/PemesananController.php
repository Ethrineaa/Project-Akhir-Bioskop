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
        $jadwal = Jadwal::with(['film', 'studio'])->findOrFail($jadwal_id);

        $kursi = Kursi::where('studio_id', $jadwal->studio_id)
            ->orderBy('nomor_kursi')
            ->get();

        return view('user.kursi.index', compact('jadwal', 'kursi'));
    }

    // =============================
    // CHECKOUT + MIDTRANS SNAP
    // =============================
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'seats'     => 'required|string',
        ]);

        $seats = explode(',', $request->seats);
        $jumlahTiket = count($seats);

        if ($jumlahTiket < 1) {
            return response()->json([
                'message' => 'Pilih minimal satu kursi'
            ], 422);
        }

        $jadwal = Jadwal::with('film')->findOrFail($request->jadwal_id);

        // =============================
        // VALIDASI KURSI
        // =============================
        $validSeatCount = Kursi::where('studio_id', $jadwal->studio_id)
            ->whereIn('nomor_kursi', $seats)
            ->count();

        if ($validSeatCount !== $jumlahTiket) {
            return response()->json([
                'message' => 'Kursi tidak valid'
            ], 422);
        }

        $totalHarga = $jumlahTiket * $jadwal->film->harga;

        // =============================
        // SIMPAN PEMESANAN + PEMBAYARAN
        // =============================
        $pemesanan = DB::transaction(function () use ($jadwal, $jumlahTiket, $totalHarga) {
            $pemesanan = Pemesanan::create([
                'jadwal_id'    => $jadwal->id,
                'user_id'      => Auth::id(),
                'jumlah_tiket' => $jumlahTiket,
                'total_harga'  => $totalHarga,
            ]);

            Pembayaran::create([
                'pemesanan_id' => $pemesanan->id,
                'status'       => 'pending',
            ]);

            return $pemesanan;
        });

        // =============================
        // MIDTRANS CONFIG
        // =============================
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        $params = [
            'transaction_details' => [
                'order_id'     => 'ORDER-' . $pemesanan->id . '-' . time(),
                'gross_amount' => $pemesanan->total_harga,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name ?? 'Customer',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'snapToken'    => $snapToken,
            'pemesanan_id' => $pemesanan->id,
        ]);
    }

    // =============================
    // RIWAYAT PEMESANAN USER
    // =============================
    public function index()
    {
        $pemesanan = Pemesanan::with(['jadwal.film', 'pembayaran'])
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
}
