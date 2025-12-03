<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function show($id)
    {
        // Ambil film berdasarkan ID
        $film = Film::with('jadwals.studio')->findOrFail($id);

        return view('user.films.show', compact('film'));
    }
}
