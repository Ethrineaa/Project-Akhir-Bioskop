@extends('layouts.landing')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-10 text-white">

        {{-- ======================
        HEADER
    ====================== --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold">
                {{ $jadwal->film->judul }}
            </h1>
            <p class="text-sm text-gray-400">
                {{ $jadwal->film->genre->nama }} •
                Studio {{ $jadwal->studio->nama }} •
                {{ \Carbon\Carbon::parse($jadwal->jam)->format('H:i') }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ======================
            SEAT MAP
        ====================== --}}
            <div class="lg:col-span-2 bg-[#0B1220] rounded-2xl p-6">

                {{-- LEGEND --}}
                <div class="flex justify-center flex-wrap gap-6 text-sm mb-6">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 border border-blue-500 rounded-md"></span>
                        <span>Tersedia</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 bg-blue-600 opacity-60 rounded-md"></span>
                        <span>Sudah dipesan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 bg-blue-600 rounded-md"></span>
                        <span>Dipilih</span>
                    </div>
                </div>

                {{-- SCREEN --}}
                <div class="relative text-center mb-10">
                    <div class="h-[2px] bg-gradient-to-r from-transparent via-blue-500 to-transparent mb-2"></div>
                    <p class="tracking-widest text-gray-400 text-sm">SCREEN</p>
                </div>

                {{-- ======================
                SEATS
            ====================== --}}
                @php
                    $groupedSeats = $kursi
                        ->groupBy(fn($item) => substr($item->nomor_kursi, 0, 1))
                        ->map(fn($seats) => $seats->sortBy(fn($seat) => intval(substr($seat->nomor_kursi, 1))));
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
                                    class="seat w-10 py-2 text-sm rounded-md
                                       border border-blue-500 text-blue-400
                                       hover:bg-blue-600 hover:text-white transition"
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

                <h3 class="text-lg font-semibold mb-1">Selected Seats</h3>
                <p class="text-sm text-gray-400 mb-4">
                    {{ $jadwal->film->judul }} • {{ \Carbon\Carbon::parse($jadwal->jam)->format('H:i') }}
                </p>

                {{-- LIST --}}
                <div id="seatList" class="space-y-3 mb-6">
                    <p class="text-gray-500 text-sm">No seat selected</p>
                </div>

                {{-- PRICE --}}
                <div class="border-t border-gray-700 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">
                            Tickets (<span id="ticketCount">0</span>)
                        </span>
                        <span>$<span id="ticketTotal">0.00</span></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Booking Fee</span>
                        <span>$2.00</span>
                    </div>
                </div>

                {{-- TOTAL --}}
                <div class="flex justify-between items-center mt-4 text-lg font-semibold">
                    <span>Total</span>
                    <span class="text-blue-500">$<span id="grandTotal">0.00</span></span>
                </div>

                {{-- FORM --}}
                <form action="{{ route('user.pemesanan.store') }}" method="POST" class="mt-6">
                    @csrf
                    <input type="hidden" id="seatInput" name="seats">
                    <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

                    <button
                        class="w-full bg-blue-600 hover:bg-blue-700 transition py-3 rounded-xl font-semibold flex items-center justify-center gap-2">
                        Pay Now
                    </button>
                </form>
            </div>

        </div>
    </div>

   

    {{-- ======================
    SCRIPT
====================== --}}
    <script>
        const seatPrice = 14;
        const bookingFee = 2;
        let selected = [];

        const seatList = document.getElementById('seatList');
        const seatInput = document.getElementById('seatInput');
        const ticketCount = document.getElementById('ticketCount');
        const ticketTotal = document.getElementById('ticketTotal');
        const grandTotal = document.getElementById('grandTotal');

        function renderSummary() {
            seatList.innerHTML = '';

            if (selected.length === 0) {
                seatList.innerHTML = `<p class="text-gray-500 text-sm">No seat selected</p>`;
            } else {
                selected.forEach(seat => {
                    seatList.innerHTML += `
                    <div class="flex justify-between items-center bg-[#0B1220] rounded-xl px-4 py-3">
                        <div>
                            <p class="font-medium">Row ${seat.charAt(0)}, Seat ${seat.slice(1)}</p>
                            <p class="text-xs text-gray-400">Standard</p>
                        </div>
                        <span class="font-medium">$${seatPrice.toFixed(2)}</span>
                    </div>
                `;
                });
            }

            ticketCount.innerText = selected.length;
            ticketTotal.innerText = (selected.length * seatPrice).toFixed(2);
            grandTotal.innerText = (selected.length * seatPrice + bookingFee).toFixed(2);
            seatInput.value = selected.join(',');
        }

        document.querySelectorAll('.seat').forEach(btn => {
            btn.addEventListener('click', function() {
                const seat = this.dataset.seat;

                if (selected.includes(seat)) {
                    selected = selected.filter(s => s !== seat);
                    this.classList.remove('bg-blue-600', 'text-white');
                    this.classList.add('border', 'border-blue-500', 'text-blue-400');
                } else {
                    selected.push(seat);
                    this.classList.remove('border', 'border-blue-500', 'text-blue-400');
                    this.classList.add('bg-blue-600', 'text-white');
                }

                renderSummary();
            });
        });
    </script>
@endsection
