<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Film;
use App\Models\Studio;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    protected $title = 'Jadwal';

    public function index()
    {
        $title = 'Jadwal Tayang';

        $jadwals = Jadwal::with(['film', 'studio'])
            ->orderBy('tanggal', 'DESC')
            ->orderBy('jam', 'ASC')
            ->get();

        return view('admin.jadwal.index', compact('jadwals', 'title'));
    }

    public function create()
    {
        return view('admin.jadwal.create', [
            'films' => Film::all(),
            'studios' => Studio::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            'tanggal' => 'required|date',
            'jam' => 'required',
        ]);

        $film = Film::findOrFail($request->film_id);
        $durasi = $film->durasi; // dalam menit

        // Waktu awal
        $mulai = Carbon::parse($request->tanggal . ' ' . $request->jam);

        // Waktu selesai film (jam mulai + durasi)
        $selesai_film = $mulai->copy()->addMinutes($durasi);

        // Tambah buffer 30 menit
        $selesai_total = $selesai_film->copy()->addMinutes(30);

        // Cek jadwal studio di tanggal yang sama
        $existingSchedules = Jadwal::where('studio_id', $request->studio_id)->where('tanggal', $request->tanggal)->get();

        foreach ($existingSchedules as $jadwal) {
            $jadwal_mulai = Carbon::parse($jadwal->tanggal . ' ' . $jadwal->jam);
            $jadwal_selesai = $jadwal_mulai->copy()->addMinutes($jadwal->film->durasi)->addMinutes(30);

            // Cek bentrok
            if ($mulai->between($jadwal_mulai, $jadwal_selesai) || $selesai_total->between($jadwal_mulai, $jadwal_selesai) || $jadwal_mulai->between($mulai, $selesai_total)) {
                return back()->with('error', 'Jadwal bentrok dengan jadwal lain di studio ini!');
            }
        }

        // Simpan jadwal
        Jadwal::create([
            'film_id' => $request->film_id,
            'studio_id' => $request->studio_id,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit(Jadwal $jadwal)
    {
        return view('admin.jadwal.edit', [
            'jadwal' => $jadwal,
            'films' => Film::all(),
            'studios' => Studio::all(),
        ]);
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            'tanggal' => 'required|date',
            'jam' => 'required',
        ]);

        $film = Film::findOrFail($request->film_id);
        $durasi = $film->durasi;

        // waktu mulai
        $mulai = Carbon::parse($request->tanggal . ' ' . $request->jam);

        // waktu selesai
        $selesai = $mulai->copy()->addMinutes($durasi + 30);

        // cek bentrok, kecuali jadwal yg sedang diedit
        $existing = Jadwal::where('studio_id', $request->studio_id)->where('tanggal', $request->tanggal)->where('id', '!=', $jadwal->id)->get();

        foreach ($existing as $j) {
            $jMulai = Carbon::parse($j->tanggal . ' ' . $j->jam);
            $jSelesai = $jMulai->copy()->addMinutes($j->film->durasi + 30);

            if ($mulai->between($jMulai, $jSelesai) || $selesai->between($jMulai, $jSelesai) || $jMulai->between($mulai, $selesai)) {
                return back()->with('error', 'Jadwal bentrok dengan jadwal lain!');
            }
        }

        // update jadwal
        $jadwal->update([
            'film_id' => $request->film_id,
            'studio_id' => $request->studio_id,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $film = Film::findOrFail($id);

        if ($film->jadwals()->count() > 0) {
            return back()->with('error', 'Film tidak bisa dihapus karena masih memiliki jadwal tayang.');
        }

        $film->delete();
        return back()->with('success', 'Film berhasil dihapus.');
    }
}
