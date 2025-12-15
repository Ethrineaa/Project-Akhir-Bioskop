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

                {{-- ======================
                LEGEND (SESUAI CONTOH)
            ====================== --}}
                <div class="mt-3 mb-6 px-3">
                    <div class="flex justify-center flex-wrap gap-4 text-sm">

                        {{-- Tersedia --}}
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 border border-blue-500 rounded-md"></span>
                            <span class="text-gray-300">Tersedia</span>
                        </div>

                        {{-- Sudah Dipesan --}}
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 bg-blue-600 opacity-60 rounded-md"></span>
                            <span class="text-gray-300">Sudah dipesan</span>
                        </div>

                        {{-- Dipilih --}}
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 bg-blue-600 rounded-md"></span>
                            <span class="text-gray-300">Dipilih</span>
                        </div>

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
                    $groupedSeats = $kursi
                        ->groupBy(fn($item) => substr($item->nomor_kursi, 0, 1))
                        ->map(function ($seats) {
                            return $seats->sortBy(function ($seat) {
                                return intval(substr($seat->nomor_kursi, 1));
                            });
                        });
                @endphp


                <div class="space-y-4">
                    @foreach ($groupedSeats as $row => $seats)
                        <div class="grid grid-cols-11 gap-3 justify-center mx-auto w-fit">

                            @foreach ($seats->values() as $index => $item)
                                {{-- LORONG TENGAH --}}
                                @if ($seats->count() === 10 && $index === 5)
                                    <div></div>
                                @endif

                                <button type="button"
                                    class="
                                    seat
                                    border border-blue-500
                                    text-blue-400
                                    hover:bg-blue-600 hover:text-white
                                    transition
                                    rounded-md
                                    text-sm
                                    py-2
                                    w-10
                                "
                                    data-seat="{{ $item->nomor_kursi }}">
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

                    <button class="w-full bg-blue-600 hover:bg-blue-700 transition py-3 rounded-xl font-semibold">
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
            btn.addEventListener('click', function() {
                const seat = this.dataset.seat;

                if (selected.includes(seat)) {
                    selected = selected.filter(s => s !== seat);
                    this.classList.remove('bg-blue-600', 'text-white');
                    this.classList.add('border-blue-500', 'text-blue-400');
                } else {
                    selected.push(seat);
                    this.classList.remove('border-blue-500', 'text-blue-400');
                    this.classList.add('bg-blue-600', 'text-white');
                }

                document.getElementById('selectedSeats').innerText =
                    selected.length ? selected.join(', ') : '-';

                document.getElementById('seatInput').value = selected.join(',');
            });
        });
    </script>
@endsection
