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
    // Halaman pilih kursi
    public function pilihKursi($jadwal_id)
    {
        $jadwal = Jadwal::with('film', 'studio')->findOrFail($jadwal_id);

        $kursi = Kursi::where('studio_id', $jadwal->studio_id)
            ->whereDoesntHave('pemesanans', function ($q) use ($jadwal) {
                $q->where('jadwal_id', $jadwal->id);
            })
            ->orderBy('nomor_kursi')
            ->get();

        return view('user.kursi.index', compact('jadwal', 'kursi'));
    }

    // Proses pemesanan
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'seats' => 'required|string'
        ]);

        $seats = array_map('intval', explode(',', $request->seats));
        $jumlahTiket = count($seats);

        if ($jumlahTiket == 0) {
            return back()->withErrors(['seats' => 'Pilih setidaknya satu kursi.']);
        }

        $jadwal = Jadwal::with('film')->findOrFail($request->jadwal_id);

        // Validasi kursi milik studio
        $validSeats = Kursi::whereIn('id', $seats)
            ->where('studio_id', $jadwal->studio_id)
            ->pluck('id')
            ->toArray();

        if (count($validSeats) != $jumlahTiket) {
            return back()->withErrors(['seats' => 'Kursi tidak valid untuk studio ini.']);
        }

        // Cek ketersediaan kursi
        $bookedSeats = Pemesanan::where('jadwal_id', $jadwal->id)
            ->whereHas('kursis', function ($q) use ($seats) {
                $q->whereIn('kursi_id', $seats);
            })
            ->exists();

        if ($bookedSeats) {
            return back()->withErrors(['seats' => 'Beberapa kursi sudah dipesan.']);
        }

        $totalHarga = $jumlahTiket * $jadwal->film->harga;

        $pemesanan = DB::transaction(function () use ($jadwal, $seats, $jumlahTiket, $totalHarga) {
            // 🔒 Simpan pemesanan
            $pemesanan = Pemesanan::create([
                'jadwal_id'     => $jadwal->id,
                'user_id'       => Auth::id(),
                'jumlah_tiket'  => $jumlahTiket,
                'total_harga'   => $totalHarga,
            ]);

            // Hubungkan kursi
            $pemesanan->kursis()->attach($seats);

            // 💳 Auto buat pembayaran
            Pembayaran::create([
                'pemesanan_id' => $pemesanan->id,
                'status' => 'waiting'
            ]);

            return $pemesanan;
        });

        // ➡️ Redirect ke halaman pembayaran
        return redirect()->route('user.pemesanan.show', $pemesanan->id);
    }

    public function index()
    {
        $pemesanan = Pemesanan::with('jadwal.film')
            ->where('user_id', Auth::id())
            ->get();

        return view('user.pemesanan.index', compact('pemesanan'));
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::with([
            'jadwal.film',
            'kursis',
            'pembayaran'
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.pemesanan.show', compact('pemesanan'));
    }
}
