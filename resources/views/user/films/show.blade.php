@extends('layouts.landing')

@section('content')

    {{-- ======================
        HERO SECTION
    ====================== --}}
    <div class="relative h-[380px] sm:h-[420px] md:h-[460px] -mb-8 sm:-mb-12 md:-mb-16">

        {{-- BACKGROUND --}}
        <img src="{{ asset('posters/' . $film->poster) }}"
            class="absolute inset-0 w-full h-full object-cover opacity-40">

        <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent"></div>

        {{-- HERO CONTENT --}}
        <div class="relative flex items-start pt-16 sm:pt-20 md:pt-24">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 w-full">

                <div class="flex flex-col md:flex-row gap-6 md:gap-8 items-center md:items-start">

                    {{-- POSTER CARD --}}
                    <div class="w-40 sm:w-48 md:w-56 flex-shrink-0">
                        <img src="{{ asset('posters/' . $film->poster) }}"
                            class="rounded-xl shadow-2xl border border-gray-800 w-full">
                    </div>

                    {{-- INFO FILM --}}
                    <div class="text-center md:text-left">

                        <h1 class="text-2xl sm:text-3xl md:text-5xl font-extrabold leading-tight">
                            {{ $film->judul }}
                        </h1>

                        {{-- TAG --}}
                        <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-4 text-xs">
                            <span class="px-3 py-1 rounded-full bg-emerald-600">
                                {{ $film->genre->nama }}
                            </span>
                            <span class="px-3 py-1 rounded-full bg-gray-700">
                                {{ $film->durasi }} Menit
                            </span>
                        </div>

                        {{-- HARGA --}}
                        <div class="mt-5">
                            <span class="text-xl sm:text-2xl font-bold text-emerald-400">
                                Rp {{ number_format($film->harga, 0, ',', '.') }}
                            </span>
                        </div>

                        {{-- SINOPSIS --}}
                        <div class="mt-6 max-w-3xl">
                            <h2 class="text-lg sm:text-xl font-bold mb-2">Sinopsis</h2>
                            <p class="text-gray-300 leading-relaxed text-sm sm:text-base text-justify">
                                {{ $film->sinopsis }}
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ======================
        JADWAL SECTION
    ====================== --}}
    <div id="jadwal"
        class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 pt-24 pb-14"
        x-data="{ selectedDay: null }">

        <h2 class="text-xl sm:text-2xl font-bold mb-6">
            Pilih Hari Tayang
        </h2>

        @php
            $days = [];
            for ($i = 0; $i < 7; $i++) {
                $days[] = now()->addDays($i);
            }
        @endphp

        {{-- PILIH HARI --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3">
            @foreach ($days as $day)
                @php $dayKey = $day->format('Y-m-d'); @endphp
                <button
                    @click="selectedDay = '{{ $dayKey }}'"
                    class="p-3 sm:p-4 rounded-xl bg-gray-900 hover:bg-gray-800 border border-gray-700">
                    <p class="font-semibold text-sm sm:text-base">
                        {{ $day->translatedFormat('D') }}
                    </p>
                    <p class="text-xs sm:text-sm text-gray-400">
                        {{ $day->format('d M') }}
                    </p>
                </button>
            @endforeach
        </div>

        {{-- LIST JADWAL --}}
        <div class="mt-10">
            @foreach ($days as $day)
                @php
                    $dayKey = $day->format('Y-m-d');
                    $filtered = $film->jadwals->filter(fn($j) => $j->tanggal === $dayKey);
                @endphp

                <div x-show="selectedDay === '{{ $dayKey }}'">
                    <h3 class="text-lg sm:text-xl font-semibold mb-4">
                        Jadwal {{ $day->translatedFormat('l, d M') }}
                    </h3>

                    @if ($filtered->isEmpty())
                        <p class="text-gray-400">
                            Tidak ada jadwal tersedia
                        </p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($filtered as $jadwal)
                                @php
                                    $total = $jadwal->studio->kursis->count();
                                    $booked = $jadwal->pemesanan()->count();
                                    $available = $total - $booked;
                                @endphp

                                <div class="p-5 rounded-xl bg-gray-900 border border-gray-800">
                                    <p class="font-bold">
                                        {{ $jadwal->studio->nama }}
                                    </p>

                                    <p class="text-sm text-gray-400 mt-1">
                                        {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                    </p>

                                    <p class="text-sm mt-3">
                                        Kursi tersedia:
                                        <span class="font-bold text-emerald-400">
                                            {{ $available }}
                                        </span>
                                        / {{ $total }}
                                    </p>

                                    <a href="{{ route('user.kursi.indexw', $jadwal->id) }}"
                                        class="block mt-4 text-center bg-emerald-600 hover:bg-emerald-500 py-2 rounded-lg">
                                        Pesan Tiket
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- ======================
        TOMBOL KEMBALI (PALING BAWAH)
    ====================== --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pb-16">
        <a href="{{ url()->previous() }}"
            class="inline-flex items-center gap-2 px-6 py-3 text-sm sm:text-base font-semibold
                    rounded-xl bg-gray-800 hover:bg-gray-700
                    border border-gray-600 transition">
            ← Kembali ke Daftar Film
        </a>
    </div>

@endsection
