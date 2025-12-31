@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Data {{ $title }}</h5>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table id="datatable" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pemesanan</th>
                        <th>Jumlah Tiket</th>
                        <th>Total Harga</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($pemesanans as $index => $pemesanan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>#{{ $pemesanan->id }}</td>
                            <td>{{ $pemesanan->jumlah_tiket }}</td>
                            <td>
                                Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                            </td>
                            <td>
                                <span
                                    class="badge
                    {{ $pemesanan->pembayaran?->status === 'success'
                        ? 'bg-success'
                        : ($pemesanan->pembayaran?->status === 'pending'
                            ? 'bg-warning'
                            : 'bg-secondary') }}">
                                    {{ ucfirst($pemesanan->pembayaran->status ?? 'Belum Bayar') }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.pemesanan.show', $pemesanan->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>


            </table>
        </div>
    </div>
@endsection
