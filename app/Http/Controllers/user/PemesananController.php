<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\Pemesanan;

class PemesananController extends Controller
{
    // Halaman pilih kursi
    public function pilihKursi($jadwal_id)
    {
        $jadwal = Jadwal::with('film', 'studio')->findOrFail($jadwal_id);

        $kursi = Kursi::where('studio_id', $jadwal->studio_id)
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

        $selectedSeats = array_filter(array_map('trim', explode(',', $request->seats)));

        if (count($selectedSeats) === 0) {
            return back()->with('error', 'Pilih minimal satu kursi.');
        }

        DB::beginTransaction();

        try {
            // Ambil data kursi berdasarkan nomor_kursi
            $kursi = Kursi::whereIn('nomor_kursi', $selectedSeats)
                ->where('studio_id', function ($q) use ($request) {
                    $q->select('studio_id')->from('jadwals')
                        ->where('id', $request->jadwal_id)->limit(1);
                })
                ->get();

            if ($kursi->count() != count($selectedSeats)) {
                DB::rollBack();
                return back()->with('error', 'Kursi tidak valid atau tidak ditemukan.');
            }

            // Cek jika kursi sudah pernah dipesan di jadwal tersebut
            $sudahDipesan = DB::table('kursi_pemesanan')
                ->join('pemesanans', 'kursi_pemesanan.pemesanan_id', '=', 'pemesanans.id')
                ->where('pemesanans.jadwal_id', $request->jadwal_id)
                ->whereIn('kursi_pemesanan.kursi_id', $kursi->pluck('id'))
                ->exists();

            if ($sudahDipesan) {
                DB::rollBack();
                return back()->with('error', 'Salah satu kursi sudah terpesan.');
            }

            // Harga satu kursi (contoh)
            $hargaPerKursi = 50000;

            // Buat pemesanan
            $pemesanan = Pemesanan::create([
                'jadwal_id' => $request->jadwal_id,
                'user_id' => Auth::id(),
                'jumlah_tiket' => count($selectedSeats),
                'total_harga' => count($selectedSeats) * $hargaPerKursi,
            ]);

            // Insert ke pivot kursi_pemesanan
            foreach ($kursi as $k) {
                DB::table('kursi_pemesanan')->insert([
                    'pemesanan_id' => $pemesanan->id,
                    'kursi_id' => $k->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            return redirect()->route('user.pemesanan.show', $pemesanan->id)
                ->with('success', 'Pemesanan berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
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
            'kursis'
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.pemesanan.show', compact('pemesanan'));
    }
}
