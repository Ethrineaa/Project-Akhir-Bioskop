@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Detail Pemesanan #{{ $pemesanan->id }}</h3>

    <ul>
        <li>Film: {{ $pemesanan->jadwal->film->judul ?? '-' }}</li>
        <li>Studio: {{ $pemesanan->jadwal->studio->nama ?? '-' }}</li>
        <li>Jumlah Tiket: {{ $pemesanan->jumlah_tiket }}</li>
        <li>Total Harga: Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</li>
        <li>Status Pembayaran: {{ $pemesanan->pembayaran->status ?? 'Belum Bayar' }}</li>
    </ul>

    <a href="{{ route('admin.pemesanan.index') }}" class="btn btn-secondary">
        Kembali
    </a>
</div>
@endsection

    