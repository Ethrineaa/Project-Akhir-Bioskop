<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PemesananController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user) {
            $user->update([
                'last_view_payment' => now(),
            ]);
        }

        $pemesanans = Pemesanan::with(['jadwal.film', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.pemesanans.index', compact('pemesanans'));
    }

    public function show(Pemesanan $pemesanan)
    {
        $pemesanan->load(['jadwal.film.genre', 'jadwal.studio', 'kursi']);
        return view('user.pemesanans.show', compact('pemesanan'));
    }

    public function layoutKursi(Pemesanan $pemesanan)
    {
        $jadwal = $pemesanan->jadwal;

        // semua kursi di studio
        $kursi = Kursi::where('studio_id', $jadwal->studio_id)->get();

        // kursi yang SUDAH dipesan di jadwal ini (oleh semua orang)
        $kursiTerpesan = DB::table('kursi_pemesanan')->join('pemesanans', 'pemesanans.id', '=', 'kursi_pemesanan.pemesanan_id')->where('pemesanans.jadwal_id', $jadwal->id)->pluck('kursi_pemesanan.kursi_id')->toArray();

        // kursi khusus pemesanan ini
        $kursiPemesanan = $pemesanan->kursi->pluck('id')->toArray();

        return view('user.pemesanans.kursi', compact('pemesanan', 'kursi', 'kursiTerpesan', 'kursiPemesanan'));
    }

    /**
     * =========================
     * PILIH KURSI
     * =========================
     */
    public function pilihKursi(Jadwal $jadwal)
    {
        $kursi = Kursi::where('studio_id', $jadwal->studio_id)->get();

        $kursiTerpesan = DB::table('kursi_pemesanan')->join('pemesanans', 'pemesanans.id', '=', 'kursi_pemesanan.pemesanan_id')->join('pembayaran', 'pembayaran.pemesanan_id', '=', 'pemesanans.id')->where('pemesanans.jadwal_id', $jadwal->id)->where('pembayaran.status', 'paid')->pluck('kursi_pemesanan.kursi_id')->toArray();

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
            'seats.*.id' => 'required|exists:kursis,id',
        ]);

        $jadwal = Jadwal::with('film')->findOrFail($request->jadwal_id);
        $seatIds = collect($request->seats)->pluck('id')->toArray();

        // CEK KURSI YANG SUDAH PAID
        $sudahTerpesan = DB::table('kursi_pemesanan')->join('pemesanans', 'pemesanans.id', '=', 'kursi_pemesanan.pemesanan_id')->join('pembayaran', 'pembayaran.pemesanan_id', '=', 'pemesanans.id')->where('pemesanans.jadwal_id', $jadwal->id)->where('pembayaran.status', 'paid')->whereIn('kursi_pemesanan.kursi_id', $seatIds)->exists();

        if ($sudahTerpesan) {
            return response()->json(['message' => 'Kursi sudah dipesan'], 409);
        }

        DB::beginTransaction();
        try {
            $jumlahTiket = count($seatIds);
            $totalHarga = $jumlahTiket * $jadwal->film->harga;

            // 1️⃣ SIMPAN PEMESANAN
            $pemesanan = Pemesanan::create([
                'jadwal_id' => $jadwal->id,
                'user_id' => Auth::id(),
                'jumlah_tiket' => $jumlahTiket,
                'total_harga' => $totalHarga,
            ]);

            // 2️⃣ SIMPAN PEMBAYARAN
            Pembayaran::create([
                'pemesanan_id' => $pemesanan->id,
                'status' => 'waiting',
            ]);

            // 3️⃣ SIMPAN KURSI
            foreach ($seatIds as $kursiId) {
                DB::table('kursi_pemesanan')->insert([
                    'pemesanan_id' => $pemesanan->id,
                    'kursi_id' => $kursiId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            // ======================
            // MIDTRANS
            // ======================
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $snapToken = \Midtrans\Snap::getSnapToken([
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $pemesanan->id . '_' . time(),
                    'gross_amount' => $totalHarga,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                ],
            ]);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json(
                [
                    'message' => 'Gagal membuat pemesanan',
                ],
                500,
            );
        }
    }
}
