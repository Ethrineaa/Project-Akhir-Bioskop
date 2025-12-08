<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kursi;

class PemesananController extends Controller
{
    public function pilihKursi($jadwal_id)
    {
        $jadwal = Jadwal::with('studio')->findOrFail($jadwal_id);

        // Ambil status kursi berdasarkan jadwal
        $kursi = Kursi::where('studio_id', $jadwal->studio_id)->get();

        return view('user.kursi.index', compact('jadwal', 'kursi'));
    }
}
