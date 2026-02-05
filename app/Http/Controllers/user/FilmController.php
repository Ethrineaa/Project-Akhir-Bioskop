<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FilmController extends Controller
{
   public function show($id)
{
    $film = Film::with([
        'jadwal' => function ($query) {
            $query->whereBetween('tanggal', [
                Carbon::today(),
                Carbon::today()->addDays(6)
            ])->orderBy('tanggal');
        },
        'jadwal.studio.kursi'
    ])->findOrFail($id);

    // BUAT LIST HARI: HARI INI - 7 HARI
    $days = collect();
    for ($i = 0; $i < 7; $i++) {
        $days->push(Carbon::today()->addDays($i)->toDateString());
    }

    return view('user.films.show', compact('film', 'days'));
}

}
