@extends('layouts.landing')

@section('content')
<div class="min-h-screen bg-gray-900 text-gray-100 px-6 py-10">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- ======================
        LEFT : PILIH KURSI
        ====================== --}}
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold mb-6">
                Pilih Kursi — {{ $jadwal->film->judul }}
            </h2>

            {{-- SCREEN --}}
            <div class="mb-12 text-center">
                <div class="bg-gradient-to-r from-transparent via-gray-400 to-transparent
                            h-1.5 w-3/4 mx-auto rounded-full"></div>
                <p class="text-xs tracking-widest text-gray-400 mt-3">SCREEN</p>
            </div>

            {{-- ======================
            KURSI (5 | LORONG | 5)
            ====================== --}}
            <div class="flex justify-center">
                <div class="grid grid-cols-[repeat(5,_3rem)_3.5rem_repeat(5,_3rem)] gap-x-3 gap-y-5">
                    @foreach ($kursi as $index => $item)
                        @php
                            $col = ($index % 10) + 1;
                            $gridCol = $col > 5 ? $col + 1 : $col;
                        @endphp

                        <button
                            type="button"
                            style="grid-column: {{ $gridCol }};"
                            class="seat
                                w-12 h-12
                                rounded-lg
                                text-sm font-semibold
                                bg-gray-700 text-gray-200
                                hover:bg-gray-600
                                transition"
                            data-seat="{{ $item->nomor_kursi }}">
                            {{ $item->nomor_kursi }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- LEGEND (AREA KURSI) --}}
            <div class="flex gap-8 mt-12 text-sm text-gray-300 justify-center">
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-gray-700 rounded"></span>
                    Tersedia
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-emerald-500 rounded"></span>
                    Dipilih
                </div>
            </div>
        </div>

        {{-- ======================
        RIGHT : BOOKING SUMMARY
        ====================== --}}
        <div class="bg-gray-800 rounded-2xl p-6 h-fit sticky top-24 shadow-xl border border-gray-700">
            <h3 class="text-xl font-bold mb-6">Booking Summary</h3>

            <div class="space-y-4 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Film</span>
                    <span class="font-medium">{{ $jadwal->film->judul }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-400">Studio</span>
                    <span>{{ $jadwal->studio->nama }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-400">Jam</span>
                    <span>{{ $jadwal->jam_tayang }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-400">Kursi</span>
                    <span id="seatList" class="font-semibold">-</span>
                </div>

                <hr class="border-gray-600">

                <div class="flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span id="totalHarga">Rp 0</span>
                </div>
            </div>

            {{-- LEGEND (BOOKING) --}}
            <div class="flex gap-6 mt-6 text-xs text-gray-400">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-gray-700 rounded"></span>
                    Tersedia
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-emerald-500 rounded"></span>
                    Dipilih
                </div>
            </div>

            <button id="payBtn"
                class="mt-6 w-full bg-emerald-600 hover:bg-emerald-500
                       py-3 rounded-xl font-semibold transition">
                Proceed to Checkout →
            </button>
        </div>

    </div>
</div>

{{-- MIDTRANS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    const hargaTiket = {{ $jadwal->film->harga }};
    let selectedSeats = [];

    document.querySelectorAll('.seat').forEach(btn => {
        btn.addEventListener('click', () => {
            const seat = btn.dataset.seat;

            if (selectedSeats.includes(seat)) {
                selectedSeats = selectedSeats.filter(s => s !== seat);
                btn.classList.remove('bg-emerald-500','text-white');
                btn.classList.add('bg-gray-700','text-gray-200');
            } else {
                selectedSeats.push(seat);
                btn.classList.remove('bg-gray-700','text-gray-200');
                btn.classList.add('bg-emerald-500','text-white');
            }

            updateSummary();
        });
    });

    function updateSummary() {
        document.getElementById('seatList').innerText =
            selectedSeats.length ? selectedSeats.join(', ') : '-';

        document.getElementById('totalHarga').innerText =
            'Rp ' + (selectedSeats.length * hargaTiket).toLocaleString('id-ID');
    }
</script>
@endsection
