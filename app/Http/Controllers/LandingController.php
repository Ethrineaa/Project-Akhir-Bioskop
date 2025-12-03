<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil 4 film terbaru
        $films = Film::latest()->take(4)->get();

        // Ambil semua genre
        $genres = Genre::all();

        return view('welcome', compact('films', 'genres'));
    }
}
