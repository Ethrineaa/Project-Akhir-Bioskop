<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Pemesanan;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $chartLabels = [];
        $chartData = [];

        // ====== GRAFIK 7 HARI ======
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');

            $total = Pemesanan::whereDate('created_at', $date)
                ->whereHas('pembayaran', function ($q) {
                    $q->where('status', 'paid');
                })
                ->count();

            $chartLabels[] = now()->subDays($i)->format('D');
            $chartData[] = $total;
        }

        // ====== TOTAL TIKET TERJUAL ======
        $tiketTerjual = Pemesanan::whereHas('pembayaran', function ($q) {
            $q->where('status', 'paid');
        })->count();

        // ====== TOTAL PENJUALAN BULAN INI ======
        $penjualanBulanIni = Pemesanan::whereMonth('created_at', now()->month)
            ->whereHas('pembayaran', function ($q) {
                $q->where('status', 'paid');
            })
            ->sum('total_harga');

        // ====== FILM TAYANG HARI INI ======
        $filmsToday = Film::whereHas('jadwal', function ($q) {
            $q->whereDate('tanggal', now()->toDateString());
        })
            ->with('genre')
            ->get();

        return view(
            'admin.dashboard',
            compact(
                'chartLabels',
                'chartData',
                'tiketTerjual',
                'penjualanBulanIni',
                'filmsToday', // ✅ sekarang dikirim ke blade
            ),
        );
    }
}
