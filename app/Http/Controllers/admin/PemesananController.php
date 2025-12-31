<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;

class PemesananController extends Controller
{
    // Admin\PemesananController
    public function index()
    {
        $title = 'Pemesanan';
        $pemesanans = Pemesanan::with('jadwal.film', 'jadwal.studio')->get();

        return view('admin.pemesanan.index', compact('title', 'pemesanans'));
    }
}
