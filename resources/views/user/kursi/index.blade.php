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
                                    $isBooked = in_array($item->id, $kursiTerpesan ?? []);
                                @endphp

                                <button type="button" style="grid-column: {{ $gridCol }};" data-seat="{{ $item->id }}"
                                    data-label="{{ $item->nomor_kursi }}" @disabled($isBooked)
                                    class="seat w-12 h-12 rounded-lg text-sm font-semibold transition
                                {{ $isBooked
                                    ? 'bg-blue-300 text-blue-900 cursor-not-allowed opacity-80'
                                    : 'bg-gray-700 text-gray-200 hover:bg-blue-600 hover:text-white' }}">
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
                            <span class="w-4 h-4 bg-blue-600 rounded"></span> Dipilih
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 bg-blue-300 rounded"></span> Telah dipesan
                        </div>
                    </div>
                </div>

                {{-- ======================
            RIGHT : SUMMARY
            ====================== --}}
                <div class="bg-gray-800 rounded-2xl p-6 h-fit sticky top-24 border border-gray-700">
                    <h3 class="text-xl font-bold mb-6">Pemesanan Tiket</h3>

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
                            <span>{{ $jadwal->jam }}</span>
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

                    <div class="mt-6 flex gap-3">
                        <a href="{{ route('film.show', $jadwal->film->id) }}"
                            class="flex-1 text-center bg-gray-700 hover:bg-gray-600 py-3 rounded-xl font-semibold">
                            Back
                        </a>

                        <button id="payBtn" class="flex-1 bg-blue-600 hover:bg-blue-500 py-3 rounded-xl font-semibold">
                            Pay
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- MIDTRANS --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const redirectToShow =
            "{{ route('film.show', $jadwal->film->id) }}?from_payment=1#jadwal";

        const hargaTiket = {{ $jadwal->film->harga }};
        const jadwalId = {{ $jadwal->id }};

        let selectedSeats = [];
        let snapToken = null;

        const seatList   = document.getElementById('seatList');
        const totalHarga = document.getElementById('totalHarga');
        const payBtn     = document.getElementById('payBtn');

        // =========================
        // UPDATE SUMMARY
        // =========================
        function updateSummary() {
            seatList.textContent = selectedSeats.length
                ? selectedSeats.map(s => s.label).join(', ')
                : '-';

            totalHarga.textContent =
                'Rp ' + (selectedSeats.length * hargaTiket).toLocaleString('id-ID');

            const disabled = selectedSeats.length === 0;
            payBtn.disabled = disabled;
            payBtn.classList.toggle('opacity-50', disabled);
            payBtn.classList.toggle('cursor-not-allowed', disabled);
        }

        // =========================
        // PILIH / UNPILIH KURSI
        // =========================
        document.querySelectorAll('.seat:not([disabled])').forEach(btn => {
            btn.addEventListener('click', () => {
                const seatId = parseInt(btn.dataset.seat);
                const label  = btn.dataset.label;

                const index = selectedSeats.findIndex(s => s.id === seatId);

                if (index !== -1) {
                    // UNSELECT
                    selectedSeats.splice(index, 1);
                    btn.classList.remove('bg-blue-600', 'text-white');
                    btn.classList.add('bg-gray-700', 'text-gray-200');
                } else {
                    // SELECT
                    selectedSeats.push({ id: seatId, label });
                    btn.classList.remove('bg-gray-700', 'text-gray-200');
                    btn.classList.add('bg-blue-600', 'text-white');
                }

                updateSummary();
            });
        });

        updateSummary();

        // =========================
        // CHECKOUT → DB → MIDTRANS
        // =========================
        payBtn.addEventListener('click', async () => {
            if (selectedSeats.length === 0) return;

            payBtn.disabled = true;
            payBtn.textContent = 'Processing...';

            try {
                const response = await fetch("{{ route('user.pemesanan.checkout') }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        jadwal_id: jadwalId,
                        seats: selectedSeats.map(s => ({ id: s.id }))
                    })
                });

                if (!response.ok) {
                    const err = await response.json();
                    throw err;
                }

                const data = await response.json();

                if (!data.snap_token) {
                    throw { message: 'Snap token tidak ditemukan' };
                }

                snapToken = data.snap_token;
                openSnap();

            } catch (err) {
                console.error(err);

                Swal.fire(
                    'Gagal',
                    err.message ?? 'Terjadi kesalahan saat menyimpan pemesanan',
                    'error'
                );

                resetPayBtn();
            }
        });

        function resetPayBtn() {
            payBtn.disabled = false;
            payBtn.textContent = 'Pay';
        }

        // =========================
        // MIDTRANS SNAP
        // =========================
        function openSnap() {
            window.snap.pay(snapToken, {

                onSuccess: function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil',
                        text: 'Tiket berhasil dipesan 🎉',
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        window.location.href = redirectToShow;
                    });
                },

                onPending: function () {
                    Swal.fire(
                        'Menunggu Pembayaran',
                        'Silakan selesaikan pembayaran Anda',
                        'info'
                    );
                },

                onError: function () {
                    Swal.fire('Gagal', 'Pembayaran gagal', 'error');
                    resetPayBtn();
                },

                onClose: function () {
                    Swal.fire({
                        title: 'Pembayaran Dibatalkan',
                        text: 'Anda membatalkan proses pembayaran',
                        icon: 'warning',
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        window.location.href = redirectToShow;
                    });
                }
            });
        }
    </script>


    @endsection
