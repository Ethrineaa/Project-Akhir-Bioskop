<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;

class StrukController extends Controller
{
    public function print($id)
    {
        $pemesanan = Pemesanan::with([
            'jadwal.film',
            'jadwal.studio',
            'kursi'
        ])->findOrFail($id);

        return view('user.pemesanans.struk-print', compact('pemesanan'));
    }
}

