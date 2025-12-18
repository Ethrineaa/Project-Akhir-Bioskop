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
            'seats'     => 'required|string'
        ]);

        // seats: "A1,A2,A3"
        $seats = explode(',', $request->seats);
        $jumlahTiket = count($seats);

        if ($jumlahTiket < 1) {
            return back()->withErrors(['seats' => 'Pilih minimal satu kursi']);
        }

        $jadwal = Jadwal::with('film')->findOrFail($request->jadwal_id);

        // Validasi kursi milik studio
        $validSeatCount = Kursi::where('studio_id', $jadwal->studio_id)
            ->whereIn('nomor_kursi', $seats)
            ->count();

        if ($validSeatCount !== $jumlahTiket) {
            return back()->withErrors(['seats' => 'Kursi tidak valid']);
        }

        $totalHarga = $jumlahTiket * $jadwal->film->harga;

        $pemesanan = DB::transaction(function () use ($jadwal, $jumlahTiket, $totalHarga) {

            $pemesanan = Pemesanan::create([
                'jadwal_id'    => $jadwal->id,
                'user_id'      => Auth::id(),
                'jumlah_tiket' => $jumlahTiket,
                'total_harga'  => $totalHarga,
            ]);

            Pembayaran::create([
                'pemesanan_id' => $pemesanan->id,
                'status'       => 'waiting'
            ]);

            return $pemesanan;
        });

        // ➡️ KE HALAMAN PEMBAYARAN
        return redirect()->route('user.pemesanan.payment', $pemesanan->id);
    }

    // =============================
    // RIWAYAT PEMESANAN USER
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
        $pemesanan = Pemesanan::with([
            'jadwal.film',
            'pembayaran'
        ])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

        return view('user.pemesanan.show', compact('pemesanan'));
    }

    // =============================
    // HALAMAN PEMBAYARAN
    // =============================
    public function payment(Pemesanan $pemesanan)
    {
        abort_if($pemesanan->user_id !== Auth::id(), 403);

        $pemesanan->load('jadwal.film', 'pembayaran');

        return view('user.pemesanan.payment', compact('pemesanan'));
    }
}
