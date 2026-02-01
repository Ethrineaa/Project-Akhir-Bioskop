<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Models\Kursi;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    protected $title = 'Studio';

    public function index()
    {
        $title = 'Studio';

        $studios = Studio::withCount('kursi')->orderBy('id', 'DESC')->get();

        return view('admin.studio.index', compact('studios', 'title'));
    }

    public function create()
    {
        return view('admin.studio.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'layout' => 'nullable|string',
        ]);

        Studio::create([
            'nama' => $request->nama,
            'layout' => $request->layout,
            'kapasitas' => 0,
        ]);

        return redirect()->route('admin.studio.index')->with('success', 'Studio berhasil ditambahkan, jangan lupa tambah kursi!');
    }

    public function edit(Studio $studio)
    {
        $studio->kapasitas = $studio->kursi()->count();

        return view('admin.studio.edit', compact('studio'));
    }

    public function update(Request $request, Studio $studio)
    {
        $request->validate([
            'nama' => 'required',
            'layout' => 'nullable|string',
        ]);

        $studio->update([
            'nama' => $request->nama,
            'layout' => $request->layout,
        ]);

        $studio->kapasitas = $studio->kursi()->count();
        $studio->save();

        return redirect()->route('admin.studio.index')->with('success', 'Studio berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $studio = Studio::findOrFail($id);

        if ($studio->kursi()->count() > 0) {
            return redirect()->route('admin.studio.index')->with('error', 'Studio tidak bisa dihapus karena masih memiliki kursi.');
        }

        $studio->delete();

        return redirect()->route('admin.studio.index')->with('success', 'Studio berhasil dihapus.');
    }
}
