@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold">
            Detail Pemesanan
            <span class="text-muted">#{{ $pemesanan->id }}</span>
        </h4>

        <a href="{{ route('admin.pemesanan.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        {{-- INFO PEMESANAN --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">
                    Informasi Pemesanan
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td width="40%">Film</td>
                            <td>: {{ $pemesanan->jadwal->film->judul ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Studio</td>
                            <td>: {{ $pemesanan->jadwal->studio->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jumlah Tiket</td>
                            <td>: {{ $pemesanan->jumlah_tiket }}</td>
                        </tr>
                        <tr>
                            <td>Total Harga</td>
                            <td>
                                : <strong>Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- STATUS PEMBAYARAN --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">
                    Status Pembayaran
                </div>
                <div class="card-body">
                    @php
                        $status = $pemesanan->pembayaran->status ?? 'belum';
                    @endphp

                    <span class="badge
                        {{ $status === 'success' ? 'bg-success' : ($status === 'pending' ? 'bg-warning' : 'bg-secondary') }}
                        fs-6">
                        {{ ucfirst($status === 'belum' ? 'Belum Bayar' : $status) }}
                    </span>

                    @if($pemesanan->pembayaran)
                        <hr>
                        <p class="mb-1">
                            <strong>Metode:</strong> {{ $pemesanan->pembayaran->metode ?? '-' }}
                        </p>
                        <p class="mb-0">
                            <strong>Tanggal Bayar:</strong>
                            {{ $pemesanan->pembayaran->created_at->format('d M Y H:i') ?? '-' }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- KURSI --}}
    <div class="card shadow-sm">
        <div class="card-header fw-semibold">
            Kursi yang Dipesan
        </div>
        <div class="card-body">
            @if ($pemesanan->kursi->count())
                @foreach ($pemesanan->kursi as $kursi)
                    <span class="badge bg-primary me-1 mb-1">
                        {{ $kursi->nomor_kursi }}
                    </span>
                @endforeach
            @else
                <p class="text-muted mb-0">Tidak ada kursi</p>
            @endif
        </div>
    </div>

</div>
@endsection
