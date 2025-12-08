<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{
    public function pilihKursi($jadwal_id)
    {
        $jadwal = Jadwal::with('studio')->findOrFail($jadwal_id);

        // Ambil status kursi berdasarkan jadwal
        $kursi = Kursi::where('studio_id', $jadwal->studio_id)->get();

        return view('user.kursi.index', compact('jadwal', 'kursi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'seats' => 'required'
        ]);

        // Accept either array of seats or comma-separated string
        $seats = $request->seats;
        if (is_string($seats)) {
            $seats = array_filter(array_map('trim', explode(',', $seats)));
        }

        $jadwal = Jadwal::findOrFail($request->jadwal_id);

        DB::beginTransaction();
        try {
            foreach ($seats as $kursiKode) {
                // Attempt to reserve the seat only if it's currently available
                $updated = Kursi::where('studio_id', $jadwal->studio_id)
                    ->where('kode', $kursiKode)
                    ->where('status', 'available')
                    ->update(['status' => 'reserved']);

                if ($updated === 0) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Kursi {$kursiKode} sudah tidak tersedia.");
                }

                Pemesanan::create([
                    'user_id' => Auth::id(),
                    'jadwal_id' => $jadwal->id,
                    'kursi' => $kursiKode,
                    'status' => 'paid'
                ]);
            }

            DB::commit();
            return redirect()->route('user.dashboard')->with('success', 'Pemesanan Berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat melakukan pemesanan. Silakan coba lagi.');
        }
    }

}
