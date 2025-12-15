@extends('layouts.landing')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10 text-white">

    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold">{{ $jadwal->film->judul }}</h1>
        <p class="text-sm text-gray-400">
            {{ $jadwal->tanggal }} • {{ $jadwal->jam }}
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ======================
            SEAT MAP
        ====================== --}}
        <div class="lg:col-span-2 bg-[#120909] rounded-2xl p-6">

            {{-- LEGEND --}}
            <div class="flex gap-6 text-sm mb-6">
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-gray-700 rounded"></span> Available
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-red-600 rounded"></span> Selected
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-gray-500 rounded"></span> Occupied
                </div>
            </div>

            {{-- SCREEN --}}
            <div class="relative text-center mb-10">
                <div class="h-[2px] bg-gradient-to-r from-transparent via-red-600 to-transparent mb-2"></div>
                <p class="tracking-widest text-gray-400 text-sm">SCREEN</p>
            </div>

            {{-- SEATS --}}
            <div class="grid grid-cols-12 gap-3 justify-center">
                @foreach ($kursi as $item)
                    <button
                        type="button"
                        class="seat
                            bg-gray-700
                            hover:bg-red-500
                            transition
                            rounded-md
                            text-sm
                            py-2
                        "
                        data-id="{{ $item->id }}"
                        data-seat="{{ $item->nomor_kursi }}"
                    >
                        {{ $item->nomor_kursi }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- ======================
            SUMMARY
        ====================== --}}
        <div class="bg-[#1a0f0f] rounded-2xl p-6 h-fit">

            <h3 class="text-lg font-semibold mb-4">Selected Seats</h3>

            <div class="space-y-3 mb-6">
                <p id="selectedSeats" class="text-red-500 font-medium">
                    -
                </p>
            </div>

            <form action="{{ route('user.pemesanan.store') }}" method="POST" id="bookingForm">
                @csrf
                <input type="hidden" id="seatInput" name="seats">
                <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

                <button
                    class="w-full bg-red-600 hover:bg-red-700 transition py-3 rounded-xl font-semibold"
                >
                    Proceed to Checkout →
                </button>
            </form>

        </div>
    </div>
</div>

{{-- ======================
    SCRIPT (LOGIC ASLI)
====================== --}}
<script>
    let selected = [];

    document.querySelectorAll('.seat').forEach(btn => {
        btn.addEventListener('click', function() {
            const seat = this.dataset.seat;

            if (selected.includes(seat)) {
                selected = selected.filter(s => s !== seat);
                this.classList.remove('bg-red-600');
                this.classList.add('bg-gray-700');
            } else {
                selected.push(seat);
                this.classList.remove('bg-gray-700');
                this.classList.add('bg-red-600');
            }

            document.getElementById('selectedSeats').innerText =
                selected.length ? selected.join(', ') : '-';

            document.getElementById('seatInput').value = selected.join(',');
        });
    });
</script>
@endsection
