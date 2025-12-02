<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Film;

class UserDashboardController extends Controller
{
    public function index()
    {
        $films = Film::latest()->get();

        return view('user.dashboard', compact('films'));
    }
}
