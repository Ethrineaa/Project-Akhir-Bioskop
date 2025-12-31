@extends('layouts.landing')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10 text-white">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- =====================
        LEFT : PAYMENT METHODS
        ===================== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- VOUCHER & PROMO --}}
            <div class="bg-[#0B1220] rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold">Use Voucher Code</p>
                        <p class="text-sm text-gray-400">Available coupons: 2</p>
                    </div>
                    <span class="text-gray-400">›</span>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <div>
                        <p class="font-semibold">Redeem Points</p>
                        <p class="text-sm text-gray-400">Balance: 2.500 Pts</p>
                    </div>
                    <input type="checkbox" class="accent-blue-500">
                </div>
            </div>

            {{-- PAYMENT METHODS --}}
            <div class="bg-[#0B1220] rounded-xl p-5 space-y-6">

                <p class="text-sm text-gray-400">CHOOSE PAYMENT METHOD</p>

                {{-- CINEMA WALLET --}}
                <div
                    class="flex items-center justify-between bg-[#111827] rounded-xl p-4 border border-blue-500">
                    <div>
                        <p class="font-semibold">Cinema Wallet</p>
                        <p class="text-xs text-gray-400">
                            Balance: Rp 0 (Top up needed)
                        </p>
                    </div>
                    <span
                        class="text-xs bg-blue-600/20 text-blue-400 px-2 py-1 rounded">
                        RECOMMENDED
                    </span>
                </div>

                {{-- E-WALLET --}}
                <div>
                    <p class="text-sm text-gray-400 mb-3">E-Wallet</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach (['QRIS','DANA','OVO','GoPay'] as $wallet)
                            <button type="button"
                                class="bg-white h-14 rounded-xl flex items-center justify-center
                                       hover:ring-2 hover:ring-blue-500 transition">
                                <span class="text-black font-semibold">
                                    {{ $wallet }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- BANK TRANSFER --}}
                <div>
                    <p class="text-sm text-gray-400 mb-3">
                        Bank Transfer / Virtual Account
                    </p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach (['BCA','BRI','BNI','Mandiri'] as $bank)
                            <button type="button"
                                class="bg-white h-14 rounded-xl flex items-center justify-center
                                       hover:ring-2 hover:ring-blue-500 transition">
                                <span class="text-black font-semibold">
                                    {{ $bank }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- CREDIT CARD --}}
                <div
                    class="bg-[#111827] rounded-xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="bg-white text-black text-xs px-2 py-1 rounded">
                            VISA
                        </span>
                        <span class="bg-white text-black text-xs px-2 py-1 rounded">
                            MC
                        </span>
                        <p class="font-medium">Credit / Debit Card</p>
                    </div>
                    <span class="text-gray-400">⌄</span>
                </div>

                {{-- PAYLATER --}}
                <div>
                    <p class="text-sm text-gray-400 mb-3">Paylater</p>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach (['Kredivo','Akulaku'] as $paylater)
                            <button type="button"
                                class="bg-white h-14 rounded-xl flex items-center justify-center
                                       hover:ring-2 hover:ring-blue-500 transition">
                                <span class="text-black font-semibold">
                                    {{ $paylater }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        {{-- =====================
        RIGHT : ORDER SUMMARY
        ===================== --}}
        <div class="bg-[#0B1220] rounded-xl p-6 h-fit sticky top-24">

            <div class="flex gap-4 mb-6">
                <img
                    src="{{ asset('posters/' . $pemesanan->jadwal->film->poster) }}"
                    class="w-24 h-36 rounded-lg object-cover"
                    alt="">
                <div>
                    <p class="font-bold text-lg">
                        {{ $pemesanan->jadwal->film->judul }}
                    </p>
                    <p class="text-sm text-gray-400">
                        {{ $pemesanan->jadwal->studio->nama }}
                    </p>
                    <p class="text-sm text-gray-400">
                        {{ $pemesanan->jadwal->tanggal }} •
                        {{ $pemesanan->jadwal->jam }}
                    </p>
                    <p class="text-sm text-gray-400">
                        Seat: {{ $pemesanan->kursi }}
                    </p>
                </div>
            </div>

            <div class="space-y-3 text-sm border-t border-gray-700 pt-4">
                <div class="flex justify-between">
                    <span>Regular Seat</span>
                    <span>
                        Rp {{ number_format($pemesanan->total_harga,0,',','.') }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>Convenience Fee</span>
                    <span>Rp 4.000</span>
                </div>
            </div>

            <div class="flex justify-between items-center mt-4">
                <span class="font-semibold">Total Amount</span>
                <span class="text-xl font-bold text-blue-500">
                    Rp {{ number_format($pemesanan->total_harga + 4000,0,',','.') }}
                </span>
            </div>

            <p class="text-xs text-gray-400 mt-1">
                Includes Tax & Fees
            </p>

            <div class="mt-6 flex items-center justify-between">
                <span class="text-xs text-green-400 flex items-center gap-1">
                    🔒 Secure Payment Encrypted
                </span>
            </div>

            {{-- BUTTON PAY --}}
            <button id="pay-button"
                class="w-full mt-6 bg-blue-600 hover:bg-blue-700
                       py-3 rounded-xl font-semibold transition">
                Pay Rp {{ number_format($pemesanan->total_harga + 4000,0,',','.') }} →
            </button>
        </div>

    </div>
</div>

{{-- MIDTRANS SNAP --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    document.getElementById('pay-button').addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function (result) {
                window.location.href = "{{ route('user.pemesanan.index') }}";
            },
            onPending: function (result) {
                alert('Menunggu pembayaran');
            },
            onError: function (result) {
                alert('Pembayaran gagal');
            }
        });
    });
</script>
@endsection
