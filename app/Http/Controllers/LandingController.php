<?php

namespace App\Http\Controllers;

use App\Models\Film;

class LandingController extends Controller
{
    public function index()
    {
        $films = Film::latest()->take(4)->get();

        return view('welcome', compact('films'));
    }
}

