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
                    <div
                        class="bg-gradient-to-r from-transparent via-gray-400 to-transparent
                            h-1.5 w-3/4 mx-auto rounded-full">
                    </div>
                    <p class="text-xs tracking-widest text-gray-400 mt-3">SCREEN</p>
                </div>

                {{-- SORT KURSI --}}
                @php
                    $kursiSorted = $kursi
                        ->sortBy(function ($item) {
                            preg_match('/([A-Z]+)(\d+)/', $item->nomor_kursi, $match);
                            return $match[1] . str_pad($match[2], 3, '0', STR_PAD_LEFT);
                        })
                        ->values();
                @endphp

                {{-- KURSI --}}
                <div class="flex justify-center">
                    <div class="grid grid-cols-[repeat(5,_3rem)_3.5rem_repeat(5,_3rem)] gap-x-3 gap-y-5">
                        @foreach ($kursiSorted as $index => $item)
                            @php
                                $col = ($index % 10) + 1;
                                $gridCol = $col > 5 ? $col + 1 : $col;
                                $isBooked = in_array($item->nomor_kursi, $kursiTerpesan ?? []);
                            @endphp

                            <button type="button" style="grid-column: {{ $gridCol }};"
                                data-seat="{{ $item->nomor_kursi }}" @disabled($isBooked)
                                class="seat w-12 h-12 rounded-lg text-sm font-semibold transition
                                {{ $isBooked
                                    ? 'bg-blue-300 text-blue-900 cursor-not-allowed opacity-80'
                                    : 'bg-gray-700 text-gray-200 hover:bg-gray-600'
                                }} ">
                                {{ $item->nomor_kursi }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- LEGEND --}}
                <div class="flex gap-8 mt-12 text-sm justify-center">
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 bg-gray-700 rounded"></span> Tersedia
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 bg-emerald-500 rounded"></span> Dipilih
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 bg-red-600 rounded"></span> Telah dipesan
                    </div>
                </div>
            </div>

            {{-- ======================
        RIGHT : SUMMARY
        ====================== --}}
            <div class="bg-gray-800 rounded-2xl p-6 h-fit sticky top-24 border border-gray-700">
                <h3 class="text-xl font-bold mb-6">Booking Summary</h3>

                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Film</span>
                        <span>{{ $jadwal->film->judul }}</span>
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

                <a href="{{ route('user.films.show', $jadwal->film->id) }}">
                    <button id="payBtn"
                        class="mt-6 w-full bg-emerald-600 hover:bg-emerald-500 py-3 rounded-xl font-semibold">
                        Proceed to Checkout →
                    </button>
                </a>
            </div>
        </div>
    </div>

    {{-- MIDTRANS --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
        const hargaTiket = {{ $jadwal->film->harga }};
        let selectedSeats = [];

        const seatList = document.getElementById('seatList');
        const totalHarga = document.getElementById('totalHarga');
        const payBtn = document.getElementById('payBtn');

        function updateSummary() {
            seatList.innerText = selectedSeats.length ?
                selectedSeats.join(', ') :
                '-';

            totalHarga.innerText =
                'Rp ' + (selectedSeats.length * hargaTiket).toLocaleString('id-ID');

            payBtn.disabled = selectedSeats.length === 0;
            payBtn.classList.toggle('opacity-50', selectedSeats.length === 0);
            payBtn.classList.toggle('cursor-not-allowed', selectedSeats.length === 0);
        }

        document.querySelectorAll('.seat:not([disabled])').forEach(btn => {
            btn.addEventListener('click', () => {
                const seat = btn.dataset.seat;

                btn.classList.remove(
                    'bg-gray-700', 'text-gray-200',
                    'bg-emerald-500', 'text-white'
                );

                if (selectedSeats.includes(seat)) {
                    selectedSeats = selectedSeats.filter(s => s !== seat);
                    btn.classList.add('bg-gray-700', 'text-gray-200');
                } else {
                    selectedSeats.push(seat);
                    btn.classList.add('bg-emerald-500', 'text-white');
                }

                updateSummary();
            });
        });

        updateSummary();

        // =========================
        // MIDTRANS CHECKOUT
        // =========================
        payBtn.addEventListener('click', function() {

            if (selectedSeats.length === 0) return;

            payBtn.disabled = true;
            payBtn.innerText = 'Processing...';

            fetch('{{ route('user.pemesanan.checkout') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        jadwal_id: {{ $jadwal->id }},
                        seats: selectedSeats
                    })
                })
                .then(res => res.json())
                .then(data => {
                    window.snap.pay(data.snap_token, {
                        onClose: function() {
                            payBtn.disabled = false;
                            payBtn.innerText = 'Proceed to Checkout →';
                        }
                    });
                })
                .catch(err => {
                    alert('Gagal memproses pembayaran');
                    console.error(err);
                    payBtn.disabled = false;
                    payBtn.innerText = 'Proceed to Checkout →';
                });
        });
    </script>
@endsection
