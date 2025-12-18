<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request, Pembayaran $pembayaran)
{
    $request->validate([
        'bukti_pembayaran' => 'required|image|max:2048'
    ]);

    $file = $request->file('bukti_pembayaran');
    $nama = time() . '.' . $file->extension();
    $file->move(public_path('bukti'), $nama);

    $pembayaran->update([
        'bukti_pembayaran' => $nama,
        'status' => 'pending'
    ]);

    return back()->with('success', 'Menunggu konfirmasi admin');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
