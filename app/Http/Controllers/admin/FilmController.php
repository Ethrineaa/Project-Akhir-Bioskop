<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Genre;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    protected $title = 'Film';
    public function index()
    {
        $title = "Film";
        $films = Film::with('genre')->orderBy('id', 'DESC')->get();
        return view('admin.film.index', compact('films', 'title'));
    }

    public function create()
    {
        $genres = Genre::all();
        return view('admin.film.create', compact('genres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'sinopsis' => 'required',
            'durasi' => 'required|numeric',
            'harga' => 'required|numeric',
            'genre_id' => 'required|exists:genres,id',
            'poster' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $posterName = null;
        if ($request->hasFile('poster')) {
            $posterName = time() . '.' . $request->poster->extension();
            $request->poster->move(public_path('posters'), $posterName);
        }

        Film::create([
            'judul' => $request->judul,
            'sinopsis' => $request->sinopsis,
            'durasi' => $request->durasi,
            'harga' => $request->harga,
            'genre_id' => $request->genre_id,
            'poster' => $posterName
        ]);

        return redirect()->route('admin.film.index')->with('success', 'Film berhasil ditambahkan');
    }

    public function edit(Film $film)
    {
        $genres = Genre::all();
        return view('admin.film.edit', compact('film', 'genres'));
    }

    public function update(Request $request, Film $film)
    {
        $request->validate([
            'judul' => 'required',
            'sinopsis' => 'required',
            'durasi' => 'required|numeric',
            'harga' => 'required|numeric',
            'genre_id' => 'required|exists:genres,id',
            'poster' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $posterName = $film->poster;

        if ($request->hasFile('poster')) {
            if ($posterName && file_exists(public_path('posters/' . $posterName))) {
                unlink(public_path('posters/' . $posterName));
            }

            $posterName = time() . '.' . $request->poster->extension();
            $request->poster->move(public_path('posters'), $posterName);
        }

        $film->update([
            'judul' => $request->judul,
            'sinopsis' => $request->sinopsis,
            'durasi' => $request->durasi,
            'harga' => $request->harga,
            'genre_id' => $request->genre_id,
            'poster' => $posterName
        ]);

        return redirect()->route('admin.film.index')->with('success', 'Film berhasil diperbarui');
    }

    public function destroy(Film $film)
    {
        if ($film->poster && file_exists(public_path('posters/' . $film->poster))) {
            unlink(public_path('posters/' . $film->poster));
        }

        $film->delete();

        return redirect()->route('admin.film.index')->with('success', 'Film berhasil dihapus');
    }
}
