@extends('layouts.landing')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold mb-6">Riwayat Pemesanan</h1>

    @if($pemesanans->isEmpty())
        <p class="text-gray-400">Belum ada pemesanan tiket.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($pemesanans as $pemesanan)
                @php
                    $film = $pemesanan->jadwal->film ?? null;
                    $jadwal = $pemesanan->jadwal ?? null;
                    $pembayaran = $pemesanan->pembayaran ?? null;
                @endphp

                <div class="bg-gray-800 p-4 rounded-lg shadow">
                    <h2 class="text-xl font-semibold">{{ $film->judul ?? 'Film Tidak Ditemukan' }}</h2>
                    <p class="text-gray-400">Studio: {{ $jadwal->studio->nama ?? 'N/A' }}</p>
                    <p class="text-gray-400">Tanggal: {{ $jadwal->tanggal ?? '-' }}</p>
                    <p class="text-gray-400">Jam: {{ $jadwal->jam ?? '-' }}</p>
                    <p>Status Pembayaran:
                        <span class="{{ $pembayaran?->status == 'success' ? 'text-green-400' : 'text-red-400' }}">
                            {{ $pembayaran?->status ?? 'Belum dibayar' }}
                        </span>
                    </p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
