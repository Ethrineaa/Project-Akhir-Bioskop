@extends('layouts.landing')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10 text-white">

    <h1 class="text-3xl font-bold">{{ $jadwal->film->judul }}</h1>
    <p class="text-gray-400">{{ $jadwal->tanggal }} | {{ $jadwal->jam }}</p>

    <div class="mt-10 text-center">
        <div class="bg-gray-800 rounded-full py-3 w-2/3 mx-auto">
            SCREEN
        </div>
    </div>

    <div class="mt-10 grid grid-cols-12 gap-3 justify-center">
        @foreach ($kursi as $item)
            <button
                type="button"
                class="seat bg-gray-700 hover:bg-purple-500 px-2 py-2 rounded"
                data-id="{{ $item->id }}"
                data-seat="{{ $item->nomor_kursi }}"
            >
                {{ $item->nomor_kursi }}
            </button>
        @endforeach
    </div>

    <div class="mt-10 border-t border-gray-700 pt-4 flex justify-between items-center">
        <div>
            <p class="text-gray-400 text-sm">Selected Seats:</p>
            <p id="selectedSeats" class="font-bold text-lg">-</p>
        </div>

        <form action="{{ route('user.pemesanan.store') }}" method="POST" id="bookingForm">
            @csrf
            <input type="hidden" id="seatInput" name="seats">
            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
            <button class="bg-purple-600 px-6 py-2 rounded-lg hover:bg-purple-700">
                Confirm Booking
            </button>
        </form>
    </div>

</div>

<script>
    let selected = [];

    document.querySelectorAll('.seat').forEach(btn => {
        btn.addEventListener('click', function() {
            const seat = this.dataset.seat;

            if (selected.includes(seat)) {
                selected = selected.filter(s => s !== seat);
                this.classList.remove('bg-purple-500');
                this.classList.add('bg-gray-700');
            } else {
                selected.push(seat);
                this.classList.remove('bg-gray-700');
                this.classList.add('bg-purple-500');
            }

            document.getElementById('selectedSeats').innerText =
                selected.length ? selected.join(', ') : '-';

            document.getElementById('seatInput').value = selected.join(',');
        });
    });
</script>
@endsection
