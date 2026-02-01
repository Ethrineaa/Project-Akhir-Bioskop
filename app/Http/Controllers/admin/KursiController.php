<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kursi;
use App\Models\Studio;
use Illuminate\Http\Request;

class KursiController extends Controller
{
    protected $title = 'Kursi';

    public function index()
    {
        $title = 'Kursi';

        $kursi = Kursi::with('studio')->orderBy('studio_id')->orderBy('nomor_kursi')->get();

        return view('admin.kursi.index', compact('kursi', 'title'));
    }

    public function create()
    {
        $studios = Studio::all();
        return view('admin.kursi.create', compact('studios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'studio_id' => 'required|exists:studios,id',
            'nomor_kursi' => 'required|string',
        ]);

        Kursi::create([
            'studio_id' => $request->studio_id,
            'nomor_kursi' => $request->nomor_kursi,
        ]);

        $jumlahKursi = Kursi::where('studio_id', $request->studio_id)->count();

        Studio::where('id', $request->studio_id)->update([
            'kapasitas' => $jumlahKursi,
        ]);

        return redirect()->route('admin.kursi.index')->with('success', 'Kursi berhasil ditambahkan!');
    }

    public function edit(Kursi $kursi)
    {
        $studios = Studio::all();
        return view('admin.kursi.edit', compact('kursi', 'studios'));
    }

    public function update(Request $request, Kursi $kursi)
    {
        $request->validate([
            'studio_id' => 'required|exists:studios,id',
            'nomor_kursi' => 'required|string',
        ]);

        $kursi->update($request->all());

        return redirect()->route('admin.kursi.index')->with('success', 'Kursi berhasil diperbarui!');
    }

    public function destroy(Kursi $kursi)
    {
        $studioId = $kursi->studio_id;

        $kursi->delete();

        $jumlahKursi = Kursi::where('studio_id', $studioId)->count();

        Studio::where('id', $studioId)->update([
            'kapasitas' => $jumlahKursi,
        ]);

        return redirect()->back()->with('success', 'Kursi berhasil dihapus!');
    }
}
