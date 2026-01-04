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
                                data-label="{{ $item->nomor_kursi }}"    @disabled($isBooked)
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
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <script>
const hargaTiket = {{ $jadwal->film->harga }};
let selectedSeats = [];

const seatList = document.getElementById('seatList');
const totalHarga = document.getElementById('totalHarga');
const payBtn = document.getElementById('payBtn');

// =========================
// UPDATE SUMMARY
// =========================
function updateSummary() {
    seatList.innerText = selectedSeats.length
        ? selectedSeats.map(s => s.label).join(', ')
        : '-';

    totalHarga.innerText =
        'Rp ' + (selectedSeats.length * hargaTiket).toLocaleString('id-ID');

    payBtn.disabled = selectedSeats.length === 0;
    payBtn.classList.toggle('opacity-50', selectedSeats.length === 0);
    payBtn.classList.toggle('cursor-not-allowed', selectedSeats.length === 0);
}

// =========================
// PILIH KURSI
// =========================
document.querySelectorAll('.seat:not([disabled])').forEach(btn => {
    btn.addEventListener('click', () => {
        const seatId = btn.dataset.seat;
        const label = btn.dataset.label;

        const index = selectedSeats.findIndex(s => s.id === seatId);

        if (index !== -1) {
            // UNSELECT
            selectedSeats.splice(index, 1);
            btn.classList.remove('bg-blue-600', 'text-white');
            btn.classList.add('bg-gray-700', 'text-gray-200');
        } else {
            // SELECT
            selectedSeats.push({
                id: seatId,
                label: label
            });
            btn.classList.remove('bg-gray-700', 'text-gray-200');
            btn.classList.add('bg-blue-600', 'text-white');
        }

        updateSummary();
    });
});

updateSummary();

// =========================
// MIDTRANS CHECKOUT
// =========================
 let snapToken = null;

    payBtn.addEventListener('click', function () {

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
            if (!data.snap_token) {
                Swal.fire('Error', 'Snap token tidak ditemukan', 'error');
                resetPayBtn();
                return;
            }

            snapToken = data.snap_token;
            openSnap();
        });
    });

    function openSnap() {
        window.snap.pay(snapToken, {
            onSuccess: function () {
                window.location.href = '/user/pemesanan?status=paid';
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
                    title: 'Batalkan Pemesanan?',
                    text: 'Apakah Anda yakin ingin membatalkan pemesanan kursi?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Lanjutkan Pembayaran'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 👉 Redirect ke halaman jadwal
                        window.location.href =
                            '{{ route('film.show', $jadwal->film->id) }}';
                    } else {
                        // 👉 Buka ulang SNAP
                        openSnap();
                    }
                });
            }
        });
    }

    function resetPayBtn() {
        payBtn.disabled = false;
        payBtn.innerText = 'Pay';
    }
</script>

@endsection
