<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Support\Facades\DB;

class FilmController extends Controller
{
    public function show($id)
    {
        // Ambil film + relasi
        $film = Film::with('jadwal.studio.kursi')->findOrFail($id);

        // Hitung kursi tersedia per jadwal
        $film->jadwal->map(function ($jadwal) {

            $totalKursi = $jadwal->studio->kursi->count();

            $kursiTerpesan = DB::table('kursi_pemesanan')
                ->join('pemesanans', 'pemesanans.id', '=', 'kursi_pemesanan.pemesanan_id')
                ->join('pembayaran', 'pembayaran.pemesanan_id', '=', 'pemesanans.id')
                ->where('pemesanans.jadwal_id', $jadwal->id)
                ->whereIn('pembayaran.status', ['waiting', 'pending', 'paid'])
                ->count();

            // inject ke object jadwal
            $jadwal->available_seats = $totalKursi - $kursiTerpesan;

            return $jadwal;
        });

        return view('user.films.show', compact('film'));
    }
}
