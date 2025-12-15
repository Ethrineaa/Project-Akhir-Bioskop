@extends('layouts.landing')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10 text-white">

    {{-- ======================
        HEADER
    ====================== --}}
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
        <div class="lg:col-span-2 bg-[#0B1220] rounded-2xl p-6">

            {{-- LEGEND --}}
            <div class="flex gap-6 text-sm mb-6">
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-gray-700 rounded"></span> Available
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-blue-600 rounded"></span> Selected
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-gray-500 rounded"></span> Occupied
                </div>
            </div>

            {{-- SCREEN --}}
            <div class="relative text-center mb-10">
                <div class="h-[2px] bg-gradient-to-r from-transparent via-blue-500 to-transparent mb-2"></div>
                <p class="tracking-widest text-gray-400 text-sm">SCREEN</p>
            </div>

            {{-- ======================
                SEATS (GROUP BY ROW)
            ====================== --}}
            @php
                // contoh nomor_kursi: A1, A2, B1, B2
                $groupedSeats = $kursi->groupBy(function ($item) {
                    return substr($item->nomor_kursi, 0, 1);
                });
            @endphp

            <div class="space-y-4">
                @foreach ($groupedSeats as $row => $seats)
                    <div class="flex justify-center gap-3">

                        @foreach ($seats->values() as $index => $item)
                            <button
                                type="button"
                                class="
                                    seat
                                    bg-gray-700
                                    hover:bg-blue-500
                                    transition
                                    rounded-md
                                    text-sm
                                    py-2
                                    w-10
                                    {{ $seats->count() === 10 && $index === 5 ? 'ml-6' : '' }}
                                "
                                data-seat="{{ $item->nomor_kursi }}"
                            >
                                {{ $item->nomor_kursi }}
                            </button>
                        @endforeach

                    </div>
                @endforeach
            </div>
        </div>

        {{-- ======================
            SUMMARY
        ====================== --}}
        <div class="bg-[#111827] rounded-2xl p-6 h-fit">

            <h3 class="text-lg font-semibold mb-4">Selected Seats</h3>

            <p id="selectedSeats" class="text-blue-400 font-medium mb-6">
                -
            </p>

            <form action="{{ route('user.pemesanan.store') }}" method="POST">
                @csrf
                <input type="hidden" id="seatInput" name="seats">
                <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

                <button
                    class="w-full bg-blue-600 hover:bg-blue-700 transition py-3 rounded-xl font-semibold"
                >
                    Proceed to Checkout →
                </button>
            </form>

        </div>
    </div>
</div>

{{-- ======================
    SCRIPT
====================== --}}
<script>
    let selected = [];

    document.querySelectorAll('.seat').forEach(btn => {
        btn.addEventListener('click', function () {
            const seat = this.dataset.seat;

            if (selected.includes(seat)) {
                selected = selected.filter(s => s !== seat);
                this.classList.remove('bg-blue-600');
                this.classList.add('bg-gray-700');
            } else {
                selected.push(seat);
                this.classList.remove('bg-gray-700');
                this.classList.add('bg-blue-600');
            }

            document.getElementById('selectedSeats').innerText =
                selected.length ? selected.join(', ') : '-';

            document.getElementById('seatInput').value = selected.join(',');
        });
    });
</script>
@endsection
