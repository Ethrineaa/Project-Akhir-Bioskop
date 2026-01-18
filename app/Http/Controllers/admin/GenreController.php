<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Film;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    protected $title = 'Genre';

    public function index()
    {
        $genres = Genre::all();

        return view('admin.genre.index', compact('genres'));
    }

    public function create()
    {
        return view('admin.genre.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:genres,nama',
        ]);

        Genre::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.genre.index')->with('success', 'Genre berhasil ditambahkan');
    }

    public function edit($id)
    {
        $genre = Genre::findOrFail($id);
        return view('admin.genre.edit', compact('genre'));
    }

    public function update(Request $request, $id)
    {
        $genre = Genre::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100|unique:genres,nama,' . $genre->id,
        ]);

        $genre->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.genre.index')->with('success', 'Genre berhasil diperbarui');
    }

    public function destroy($id)
    {
        $genre = Genre::findOrFail($id);

        // Cek relasi film
        if ($genre->films()->count() > 0) {
            return redirect()->route('admin.genre.index')->with('error', 'Genre tidak bisa dihapus karena masih digunakan oleh film.');
        }

        $genre->delete();

        return redirect()->route('admin.genre.index')->with('success', 'Genre berhasil dihapus');
    }
}
