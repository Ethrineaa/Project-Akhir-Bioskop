@extends('layouts.landing')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-10">

        <h1 class="text-3xl font-bold mb-2">My Paid Tickets</h1>
        <p class="text-gray-500 mb-6">Only tickets with completed payments.</p>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search movie title..."
                    class="w-full px-4 py-2 rounded-lg
                       bg-gray-200 border border-gray-300
                       text-gray-800 placeholder-gray-500
                       focus:outline-none focus:ring-2 focus:ring-gray-400 focus:bg-gray-100">
                <svg class="absolute right-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        {{-- EMPTY STATE : NO PAID BOOKING --}}
        @if ($pemesanans->filter(fn($p) => optional($p->pembayaran)->status === 'paid')->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                <svg class="w-20 h-20 mb-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 17v-2a4 4 0 118 0v2m-9 4h10a2 2 0 002-2v-5a7 7 0 10-14 0v5a2 2 0 002 2z" />
                </svg>
                <p class="text-lg font-medium">No paid tickets yet</p>
                <p class="text-sm text-gray-500 mt-1">
                    Your paid tickets will appear here after successful payment.
                </p>
            </div>
        @else
            <!-- Booking List -->
            <div id="bookingList" class="space-y-4">

                @foreach ($pemesanans as $pemesanan)
                    @php
                        $status = optional($pemesanan->pembayaran)->status;
                    @endphp

                    {{-- HANYA TIKET PAID --}}
                    @continue($status !== 'paid')

                    @php
                        $film = $pemesanan->jadwal->film ?? null;
                        $jadwal = $pemesanan->jadwal ?? null;
                    @endphp

                    <div class="booking-item bg-white rounded-xl shadow-sm border border-gray-200
                       p-4 flex items-start space-x-4 hover:shadow-md transition"
                        data-title="{{ strtolower($film?->judul ?? '') }}">

                        <!-- Poster -->
                        <img src="{{ asset('posters/' . $film->poster) }}" class="w-24 h-36 object-cover rounded-lg">

                        <!-- Detail -->
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-900">
                                        {{ $film?->judul ?? 'Film Tidak Ditemukan' }}
                                    </h2>

                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $jadwal?->tanggal }} • {{ $jadwal?->jam }}
                                    </p>

                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $jadwal?->studio->nama ?? 'Studio belum ditentukan' }}
                                    </p>


                                    <p class="text-sm text-gray-500 mt-1">
                                        Kursi:
                                        @forelse($pemesanan->kursi as $kursi)
                                            {{ $kursi->nomor_kursi }}
                                        @empty
                                            -
                                        @endforelse
                                    </p>


                                </div>

                                <!-- Price -->
                                <div class="text-right">
                                    <span class="block text-2xl font-bold text-gray-900">
                                        Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                                    </span>

                                    <span
                                        class="inline-block mt-2 px-2 py-1 text-xs font-medium
                                         rounded-full bg-green-100 text-green-800">
                                        Paid
                                    </span>
                                </div>
                            </div>


                        </div>
                    </div>
                @endforeach

                {{-- EMPTY STATE : SEARCH NOT FOUND --}}
                <div id="searchEmpty" class="hidden flex flex-col items-center justify-center py-20 text-gray-400">
                    <svg class="w-20 h-20 mb-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 15l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    <p class="text-lg font-medium">No results found</p>
                    <p class="text-sm text-gray-500 mt-1">
                        Try searching with a different movie title.
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
