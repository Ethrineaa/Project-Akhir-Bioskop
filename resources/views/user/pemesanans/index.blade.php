@extends('layouts.landing')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold mb-2">Booking History</h1>
    <p class="text-gray-500 mb-6">Manage your upcoming shows and past experiences.</p>

    <!-- Filter Tabs -->
    <div class="flex space-x-2 mb-6">
        <button class="px-4 py-2 bg-black text-white rounded-md font-medium" onclick="filterBookings('all')">All Tickets</button>
        <button class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md font-medium" onclick="filterBookings('paid')">Paid</button>
        <button class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md font-medium" onclick="filterBookings('unpaid')">Unpaid</button>
        <button class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md font-medium" onclick="filterBookings('cancelled')">Cancelled</button>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <div class="relative">
            <input type="text" id="searchInput" placeholder="Search movie, theater, or ID..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="absolute right-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Booking List -->
    <div id="bookingList" class="space-y-4">
        @if($pemesanans->isEmpty())
            <p class="text-gray-400 text-center py-10">Belum ada pemesanan tiket.</p>
        @else
            @foreach ($pemesanans as $pemesanan)
                @php
                    $film = $pemesanan->jadwal->film ?? null;
                    $jadwal = $pemesanan->jadwal ?? null;
                    $pembayaran = $pemesanan->pembayaran ?? null;
                    $status = $pembayaran?->status ?? 'pending';
                    $totalHarga = $pemesanan->total_harga ?? 0;

                    // Jumlah tiket: jika ada kolom jumlah_tiket di tabel pemesanan
                    $jumlahTiket = $pemesanan->jumlah_tiket ?? 1; // default 1

                    // Nomor kursi: jika ada kolom nomor_kursi di pemesanan (string)
                    $kursi = $pemesanan->nomor_kursi ?? 'N/A';

                    // Status Badge Class
                    $badgeClass = match($status) {
                        'success' => 'bg-green-100 text-green-800',
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800'
                    };

                    $statusLabel = match($status) {
                        'success' => 'Paid',
                        'pending' => 'Unpaid',
                        'cancelled' => 'Cancelled',
                        default => 'Pending'
                    };
                @endphp

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-start space-x-4 group hover:shadow-md transition-shadow"
                     data-status="{{ $status }}"
                     data-title="{{ $film?->judul }}">

                    <!-- Film Poster -->
                    <img src="{{ $film?->poster ? asset('storage/' . $film->poster) : 'https://via.placeholder.com/120x180?text=No+Poster' }}"
                         alt="{{ $film?->judul }}"
                         class="w-24 h-36 object-cover rounded-lg">

                    <!-- Details -->
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">{{ $film?->judul ?? 'Film Tidak Ditemukan' }}</h2>
                                <div class="flex items-center mt-1 text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14"></path>
                                    </svg>
                                    {{ $jadwal?->tanggal ?? '-' }} • {{ $jadwal?->jam ?? '-' }}
                                </div>
                                <div class="flex items-center mt-1 text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.995 1.995 0 01-2.828 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $jadwal?->studio->nama ?? 'N/A' }} • Hall {{ $jadwal?->studio->nomor_hall ?? '?' }}
                                </div>
                                <div class="flex items-center mt-1 text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9M5 11V9m2 2a2 2 0 100 4h12a2 2 0 100-4H7z"></path>
                                    </svg>
                                    Seats: {{ $kursi }}
                                </div>
                            </div>

                            <!-- Price & Status -->
                            <div class="text-right">
                                <span class="block text-2xl font-bold text-gray-900">${{ number_format($totalHarga, 2) }}</span>
                                <span class="block text-xs text-gray-500">{{ $jumlahTiket }} ticket{{ $jumlahTiket != 1 ? 's' : '' }}</span>
                                <span class="inline-block mt-2 px-2 py-1 text-xs font-medium rounded-full {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 flex space-x-2">
                            @if($status === 'success')
                                <a href="{{ route('ticket.show', $pemesanan->id) }}"
                                   class="px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Ticket
                                </a>
                                <a href="{{ route('booking.bookAgain', $pemesanan->id) }}"
                                   class="px-3 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Book Again
                                </a>
                            @elseif($status === 'pending')
                                <a href="{{ route('payment.show', $pemesanan->id) }}"
                                   class="px-3 py-2 bg-yellow-500 text-white text-sm rounded-md hover:bg-yellow-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18v6H3v-6z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10v6M12 10v6M16 10v6"></path>
                                    </svg>
                                    Pay Now
                                </a>
                                <button onclick="cancelBooking({{ $pemesanan->id }})"
                                        class="px-3 py-2 bg-red-500 text-white text-sm rounded-md hover:bg-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A1 1 0 0117.133 20H6.867A1 1 0 016 19.133L5.133 7A1 1 0 016 6h12a1 1 0 011 1z"></path>
                                    </svg>
                                    Cancel Booking
                                </button>
                            @elseif($status === 'cancelled')
                                <button disabled class="px-3 py-2 bg-gray-300 text-gray-500 text-sm rounded-md cursor-not-allowed">
                                    Details
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Load More -->
    <div class="mt-8 text-center">
        <button class="text-blue-600 text-sm hover:underline" onclick="loadMore()">
            Load more history ▼
        </button>
    </div>

</div>

<!-- JavaScript for Filtering & Search -->
<script>
    function filterBookings(status) {
        const bookings = document.querySelectorAll('#bookingList > div');
        bookings.forEach(book => {
            if (status === 'all' || book.dataset.status === status) {
                book.style.display = 'flex';
            } else {
                book.style.display = 'none';
            }
        });
    }

    document.getElementById('searchInput').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        const bookings = document.querySelectorAll('#bookingList > div');
        bookings.forEach(book => {
            const title = book.dataset.title.toLowerCase();
            book.style.display = title.includes(query) ? 'flex' : 'none';
        });
    });

    function cancelBooking(id) {
        if (confirm('Are you sure you want to cancel this booking?')) {
            // Implement cancellation logic here (e.g., AJAX call)
            alert('Booking cancelled successfully!');
            location.reload(); // Optional: refresh page after cancellation
        }
    }

    function loadMore() {
        alert('Load more functionality not implemented yet.');
        // You can implement pagination or infinite scroll here
    }
</script>

@endsection
