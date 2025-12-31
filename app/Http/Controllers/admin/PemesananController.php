<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;

class PemesananController extends Controller
{
    public function index()
    {
        $title = 'Pemesanan';

        $pemesanans = Pemesanan::with(['user', 'jadwal.film', 'jadwal.studio', 'pembayaran'])
            ->latest()
            ->get();

        return view('admin.pemesanan.index', compact('title', 'pemesanans'));
    }
}
