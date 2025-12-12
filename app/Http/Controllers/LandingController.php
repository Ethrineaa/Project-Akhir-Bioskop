<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $genres = Genre::all();

        // Jika user memilih genre
        if ($request->genre) {
            $films = Film::where('genre_id', $request->genre)->latest()->get();
        }
        // Jika All atau tidak memilih apapun
        else {
            $films = Film::latest()->get();
        }qqq

        return view('welcome', compact('films', 'genres'));
    }
}
