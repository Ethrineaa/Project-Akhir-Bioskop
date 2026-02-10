<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;
use App\Models\Chast;
use App\Models\Chats;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $genres = Genre::all();

        if ($request->genre) {
            $films = Film::where('genre_id', $request->genre)->latest()->get();
        } else {
            $films = Film::latest()->get();
        }

        $chats = auth()->check()
            ? Chats::where('user_id', auth()->id())->orderBy('created_at')->get()
            : collect(); // kosongkan collection agar Blade tetap jalan

        return view('welcome', compact('films', 'genres', 'chats'));
    }
}
