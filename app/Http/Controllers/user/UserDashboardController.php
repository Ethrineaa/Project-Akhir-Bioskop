<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Film;

class UserDashboardController extends Controller
{
    public function index()
    {
        // Ambil film terbaru
        $films = Film::latest()->get();

        // PAKAI WELCOME SEBAGAI DASHBOARD
        return view('welcome', [
            'films' => $films
        ]);
    }
}
bubububu
