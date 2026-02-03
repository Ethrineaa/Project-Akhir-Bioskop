@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">

        <div class="mb-4">
            <h4 class="fw-semibold mb-0">Detail Pemesanan</h4>
            <small class="text-muted">ID Pemesanan #{{ $pemesanan->id }}</small>
        </div>

        <div class="row g-3">

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

            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header fw-semibold">
                        Pembayaran & Kursi
                    </div>
                    <div class="card-body">

                        @php
                            $status = $pemesanan->pembayaran->status ?? 'belum';
                        @endphp

                        <div class="mb-3">
                            <span
                                class="badge
                            {{ $status === 'success' ? 'bg-success' : ($status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}
                            px-2 py-1">
                                {{ ucfirst($status === 'belum' ? 'Belum Bayar' : $status) }}
                            </span>

                            <div class="mt-1">
                                @if ($pemesanan->pembayaran)
                                    <small class="text-muted">
                                        Dibayar pada {{ $pemesanan->pembayaran->created_at->format('d M Y H:i') }}
                                    </small>
                                @else
                                    <small class="text-muted">
                                        Belum ada transaksi pembayaran
                                    </small>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div>
                            <small class="text-muted d-block mb-2">Kursi yang dipesan</small>

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
            </div>

        </div>

        <div class="mt-4 d-flex justify-content-start">
            <a href="{{ route('admin.pemesanan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>

    </div>
@endsection
