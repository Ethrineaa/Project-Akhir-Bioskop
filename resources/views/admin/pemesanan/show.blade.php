@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-0">Detail Pemesanan</h4>
            <small class="text-muted">ID Pemesanan #{{ $pemesanan->id }}</small>
        </div>

        <a href="{{ route('admin.pemesanan.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row g-3">
        {{-- INFORMASI PEMESANAN --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-semibold">
                    Informasi Pemesanan
                </div>
                <div class="card-body">
                    <table class="table table-borderless align-middle mb-0">
                        <tr>
                            <td width="40%" class="text-muted">Film</td>
                            <td>{{ $pemesanan->jadwal->film->judul ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Studio</td>
                            <td>{{ $pemesanan->jadwal->studio->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jumlah Tiket</td>
                            <td>{{ $pemesanan->jumlah_tiket }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Harga</td>
                            <td class="fw-semibold text-success">
                                Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- STATUS PEMBAYARAN --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-semibold">
                    Status Pembayaran
                </div>
                <div class="card-body d-flex flex-column justify-content-center">

                    @php
                        $status = $pemesanan->pembayaran->status ?? 'belum';
                    @endphp

                    <div class="mb-3">
                        <span class="badge
                            {{ $status === 'success' ? 'bg-success' : ($status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}
                            fs-6 px-3 py-2">
                            {{ ucfirst($status === 'belum' ? 'Belum Bayar' : $status) }}
                        </span>
                    </div>

                    @if($pemesanan->pembayaran)
                        <small class="text-muted">
                            Dibayar pada:
                            {{ $pemesanan->pembayaran->created_at->format('d M Y H:i') }}
                        </small>
                    @else
                        <small class="text-muted">
                            Belum ada transaksi pembayaran
                        </small>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- KURSI --}}
    <div class="card shadow-sm mt-4">
        <div class="card-header fw-semibold">
            Kursi yang Dipesan
        </div>
        <div class="card-body">
            @if ($pemesanan->kursi->count())
                @foreach ($pemesanan->kursi as $kursi)
                    <span class="badge bg-primary px-3 py-2 me-1 mb-1">
                        {{ $kursi->nomor_kursi }}
                    </span>
                @endforeach
            @else
                <p class="text-muted mb-0">Tidak ada kursi yang dipesan</p>
            @endif
        </div>
    </div>

</div>
@endsection
