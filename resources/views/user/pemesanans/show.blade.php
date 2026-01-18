@extends('layouts.landing')

@section('content')
<div class="max-w-5xl mx-auto mt-10">

    <!-- BUTTON KEMBALI KE RIWAYAT -->
    <div class="mb-6">
        <a href="{{ route('user.pemesanan.index') }}"
           class="inline-block bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow">
           &larr; Kembali ke Riwayat
        </a>
    </div>

    <div class="bg-gray-900 rounded-2xl shadow-xl overflow-hidden flex">

        <!-- POSTER -->
        <div class="w-1/3">
            <img src="{{ asset('posters/' . $pemesanan->jadwal->film->poster) }}"
                 class="h-full w-full object-cover">
        </div>

        <!-- INFO -->
        <div class="w-2/3 p-6 text-gray-100 flex flex-col justify-between">

            <!-- HEADER -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="text-2xl font-bold">
                            {{ $pemesanan->jadwal->film->judul }}
                        </h2>
                        <p class="text-gray-400 text-sm">
                            {{ $pemesanan->jadwal->film->genre->nama ?? 'Movie' }}
                        </p>
                    </div>

                    @php
                        $status = $pemesanan->pembayaran->status ?? 'waiting';
                    @endphp

                    <span class="text-white text-xs px-3 py-1 rounded-full
                        {{ $status == 'paid' ? 'bg-green-500' : 'bg-yellow-500' }}">
                        {{ strtoupper($status) }}
                    </span>
                </div>

                <!-- INFO GRID -->
                <div class="grid grid-cols-4 gap-4 text-sm mb-6">

                    @php
                        $info = [
                            ['DATE', $pemesanan->jadwal->tanggal, 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                            ['TIME', $pemesanan->jadwal->jam, 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['STUDIO', $pemesanan->jadwal->studio->nama, 'M3 21h18M9 8h6m-6 4h6m-6 4h6M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16'],
                            ['SEAT', $pemesanan->kursi->pluck('nomor_kursi')->implode(', '), 'M6 18v-5a3 3 0 013-3h6a3 3 0 013 3v5M5 21h14']
                        ];
                    @endphp

                    @foreach($info as [$label,$value,$path])
                    <div class="bg-gray-800 rounded-lg p-3 text-center">
                        <div class="flex justify-center mb-1 text-purple-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                            </svg>
                        </div>
                        <p class="text-gray-400">{{ $label }}</p>
                        <p class="font-semibold">{{ $value }}</p>
                    </div>
                    @endforeach

                </div>

                <!-- TOTAL -->
                <div class="border-t border-gray-700 pt-4 text-sm">
                    <div class="flex justify-between text-purple-400 font-bold text-lg">
                        <span>Total Amount</span>
                        <span>Rp {{ number_format($pemesanan->total_harga,0,',','.') }}</span>
                    </div>
                </div>
            </div>

            <!-- QR SECTION -->
            @php
                $bookingCode = 'TX-' . str_pad($pemesanan->id, 6, '0', STR_PAD_LEFT);
            @endphp

            <div class="mt-6 bg-gray-800 rounded-xl p-4 flex items-center justify-between">

                <!-- QR -->
                <div class="flex items-center gap-4">
                    <div class="bg-white p-2 rounded-lg shadow">
                        <img class="w-24 h-24"
                             src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $bookingCode }}">
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">BOOKING ID</p>
                        <p class="font-semibold tracking-widest text-lg">{{ $bookingCode }}</p>
                        <p class="text-xs text-gray-500 mt-1">Scan at cinema entrance</p>
                    </div>
                </div>

                <!-- BUTTON LAYOUT KURSI -->
                <a href="{{ route('user.pemesanan.kursi', $pemesanan->id) }}"
                   class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-3 rounded-lg text-sm font-semibold shadow">
                     Layout Kursi
                </a>

            </div>

        </div>
    </div>
</div>
@endsection
