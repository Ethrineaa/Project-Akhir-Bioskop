@extends('layouts.landing')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10 text-gray-100">

    <!-- BUTTON KEMBALI KE HALAMAN AWAL -->
    <div class="mb-6">
        <a href="{{ route('user.dashboard') }}"
           class="inline-block bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow">
           &larr; Kembali ke Dashboard
        </a>
    </div>

    <h1 class="text-3xl font-bold mb-2">Tiket Saya</h1>
    <p class="text-gray-400 mb-6">Hanya tiket dengan pembayaran berhasil.</p>

    <!-- Search Bar -->
    <div class="mb-6">
        <div class="relative">
            <input type="text" id="searchInput" placeholder="Cari judul film..."
                class="w-full px-4 py-2 rounded-lg
                   bg-gray-800 border border-gray-700
                   text-gray-200 placeholder-gray-500
                   focus:outline-none focus:ring-2 focus:ring-purple-500">
            <svg class="absolute right-3 top-2.5 h-5 w-5 text-gray-500" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    {{-- EMPTY STATE & LISTING TIKET --}}
    @if ($pemesanans->filter(fn($p) => optional($p->pembayaran)->status === 'paid')->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
            <div class="text-6xl mb-4">🎟️</div>
            <p class="text-lg font-medium text-gray-200">Belum ada tiket yang dibayar</p>
            <p class="text-sm text-gray-500 mt-1">
                Tiket yang sudah berhasil dibayar akan muncul di sini.
            </p>
        </div>
    @else
        <!-- Daftar Tiket -->
        <div id="bookingList" class="space-y-4">

            @foreach ($pemesanans as $pemesanan)
                @php
                    $status = optional($pemesanan->pembayaran)->status;
                @endphp
                @continue($status !== 'paid')

                @php
                    $film = $pemesanan->jadwal->film ?? null;
                    $jadwal = $pemesanan->jadwal ?? null;
                @endphp

                <div class="booking-item
            bg-gray-900/80 border border-gray-700
            rounded-xl p-4 flex items-start space-x-4
            hover:border-purple-500/70 hover:bg-gray-900 transition"
                    data-title="{{ strtolower($film?->judul ?? '') }}">

                    <!-- Poster -->
                    <img src="{{ asset('posters/' . $film->poster) }}" class="w-24 h-36 object-cover rounded-lg">

                    <!-- Detail -->
                    <div class="flex-1">
                        <div class="flex justify-between items-start">

                            <div>
                                <h2 class="text-xl font-semibold text-gray-100">
                                    {{ $film?->judul ?? 'Film Tidak Ditemukan' }}
                                </h2>

                                <p class="text-sm text-gray-400 mt-1">
                                    {{ $jadwal?->tanggal }} • {{ $jadwal?->jam }}
                                </p>

                                <p class="text-sm text-gray-400 mt-1">
                                    {{ $jadwal?->studio->nama ?? 'Studio belum ditentukan' }}
                                </p>

                                <p class="text-sm text-gray-400 mt-1">
                                    Kursi:
                                    @forelse($pemesanan->kursi as $kursi)
                                        {{ $kursi->nomor_kursi }}
                                    @empty
                                        -
                                    @endforelse
                                </p>

                                <a href="{{ route('user.pemesanan.show', $pemesanan->id) }}"
                                    class="inline-block mt-3 px-3 py-1 bg-purple-600 text-white rounded text-sm hover:bg-purple-700">
                                    Detail Tiket
                                </a>
                            </div>

                            <!-- Harga -->
                            <div class="text-right">
                                <span class="block text-2xl font-bold text-gray-100">
                                    Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                                </span>

                                <span
                                    class="inline-block mt-2 px-2 py-1 text-xs font-medium
                                    rounded-full bg-green-500/20 text-green-300">
                                    Paid
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach

            {{-- EMPTY STATE : HASIL PENCARIAN KOSONG --}}
            <div id="searchEmpty" class="hidden flex flex-col items-center justify-center py-20 text-gray-400">
                <i class="fa-solid fa-magnifying-glass text-6xl mb-4"></i>
                <p class="text-lg font-medium text-gray-200">Film tidak ditemukan</p>
                <p class="text-sm text-gray-500 mt-1">
                    Coba cari dengan judul film lain.
                </p>
            </div>

        </div>
    @endif
</div>

<!-- Search JS -->
<script>
    const searchInput = document.getElementById('searchInput');
    const items = document.querySelectorAll('.booking-item');
    const emptySearch = document.getElementById('searchEmpty');

    searchInput?.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        let visibleCount = 0;

        items.forEach(item => {
            const title = item.dataset.title;
            const match = title.includes(query);
            item.style.display = match ? 'flex' : 'none';
            if (match) visibleCount++;
        });

        emptySearch.classList.toggle('hidden', visibleCount !== 0);
    });
</script>
@endsection
