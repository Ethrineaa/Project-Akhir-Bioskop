@extends('layouts.landing')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-10 text-white">

        {{-- PAGE TITLE --}}
        <h2 class="text-2xl font-bold mb-6">Pembayaran</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- =========================
        LEFT : PAYMENT METHODS
        ========================= --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- VOUCHER --}}
                <div class="bg-[#0B1220] rounded-xl p-4">
                    <h4 class="font-semibold mb-3">Voucher & Promo</h4>

                    <div
                        class="flex justify-between items-center bg-gray-800 rounded-lg p-3 mb-3 hover:bg-gray-700 cursor-pointer">
                        <div>
                            <p class="font-medium">Gunakan Kode Voucher</p>
                            <p class="text-sm text-gray-400">Voucher tersedia</p>
                        </div>
                        <span>›</span>
                    </div>

                    <div class="flex justify-between items-center bg-gray-800 rounded-lg p-3">
                        <div>
                            <p class="font-medium">Gunakan Poin</p>
                            <p class="text-sm text-gray-400">0 Poin</p>
                        </div>
                        <input type="checkbox" class="accent-blue-500">
                    </div>
                </div>

                {{-- PAYMENT METHOD --}}
                <div class="bg-[#0B1220] rounded-xl p-4">
                    <h4 class="font-semibold mb-4">Pilih Metode Pembayaran</h4>

                    {{-- RECOMMENDED --}}
                    <div class="flex justify-between items-center bg-gray-800 rounded-lg p-4 mb-4 border border-blue-500">
                        <div>
                            <p class="font-medium">Cinema Wallet</p>
                            <p class="text-sm text-gray-400">Recommended</p>
                        </div>
                        <span class="text-blue-500">›</span>
                    </div>

                    {{-- E-WALLET --}}
                    <p class="text-sm text-gray-400 mb-2">E-Wallet</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                        @foreach (['QRIS', 'DANA', 'OVO', 'GoPay'] as $wallet)
                            <button class="bg-white text-black py-3 rounded-lg font-semibold hover:scale-105 transition">
                                {{ $wallet }}
                            </button>
                        @endforeach
                    </div>

                    {{-- BANK TRANSFER --}}
                    <p class="text-sm text-gray-400 mb-2">Transfer Bank</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach (['BCA', 'BRI', 'BNI', 'Mandiri'] as $bank)
                            <button class="bg-white text-black py-3 rounded-lg font-semibold hover:scale-105 transition">
                                {{ $bank }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- =========================
        RIGHT : ORDER SUMMARY
        ========================= --}}
            <div class="bg-[#0B1220] rounded-xl p-5 h-fit sticky top-24">

                <p class="font-semibold mb-3">Ringkasan Pesanan</p>

                <div class="flex gap-4 mb-4">
                    <img src="{{ asset('posters/' . $pemesanan->jadwal->film->poster) }}"
                        class="w-20 h-28 object-cover rounded-lg" alt="">
                    <div>
                        <p class="font-bold">
                            {{ $pemesanan->jadwal->film->judul }}
                        </p>
                        <p class="text-sm text-gray-400">
                            {{ $pemesanan->jadwal->studio->nama ?? 'Studio' }}
                        </p>
                        <p class="text-sm text-gray-400">
                            {{ $pemesanan->jadwal->tanggal }} • {{ $pemesanan->jadwal->jam }}
                        </p>
                        <p class="text-sm text-gray-400">
                            Kursi: {{ $pemesanan->kursi }}
                        </p>
                    </div>
                </div>

                <div class="border-t border-gray-700 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span>Harga Tiket</span>
                        <span>
                            Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between font-bold text-lg text-blue-500">
                        <span>Total</span>
                        <span>
                            Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                        </span>
                    </div>

                    <p class="text-xs text-gray-400">
                        Termasuk pajak & biaya layanan
                    </p>
                </div>

                {{-- PAY BUTTON --}}
                <button class="w-full mt-6 bg-blue-600 hover:bg-blue-700 py-3 rounded-xl font-semibold transition">
                    Bayar Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                </button>
            </div>

        </div>
    </div>
@endsection


andakdnkaandkasndkand
sdnskndkasndknslkd
adnasndlkandlkasndklas
