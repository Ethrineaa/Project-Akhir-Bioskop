@extends('layouts.landing')

@section('content')
<div class="max-w-xl mx-auto px-4 py-10 text-white">

    <h2 class="text-2xl font-bold mb-4">Pembayaran</h2>

    <div class="bg-[#0B1220] rounded-xl p-4 mb-6">
        <p class="font-semibold">
            {{ $pemesanan->jadwal->film->judul }}
        </p>
        <p class="text-sm text-gray-400">
            Total Bayar
        </p>
        <p class="text-xl text-blue-500 font-bold">
            Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
        </p>
    </div>

    <h4 class="mb-3 font-semibold">Pilih Metode Pembayaran</h4>

    <div class="grid grid-cols-2 gap-3">
        <button class="bg-gray-800 py-3 rounded-lg">QRIS</button>
        <button class="bg-gray-800 py-3 rounded-lg">DANA</button>
        <button class="bg-gray-800 py-3 rounded-lg">OVO</button>
        <button class="bg-gray-800 py-3 rounded-lg">Transfer Bank</button>
    </div>

</div>
@endsection
w
