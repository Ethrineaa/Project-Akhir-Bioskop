<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Genre;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        // Ambil film beserta relasi genre
        $films = Film::with('genre')->latest()->get();
        $genres = Genre::all();

        return view('welcome', compact('films', 'genres'));
    }
}
