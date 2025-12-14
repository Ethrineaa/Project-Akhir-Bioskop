@extends('layouts.landing')

@section('content')
    <div class="relative min-h-screen bg-black text-white">

        {{-- ======================
    HERO SECTION
====================== --}}
        <div class="relative">

            {{-- POSTER LANDSCAPE (BACKGROUND) --}}
            <img src="{{ asset('posters/' . $film->poster) }}" alt="{{ $film->judul }}"
                class="w-full h-[520px] object-cover opacity-40">

            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent"></div>

            <div class="absolute inset-0 flex items-center">
                <div class="max-w-6xl mx-auto px-6 w-full">

                    <div class="flex flex-col md:flex-row gap-8 items-center md:items-start">

                        {{-- POSTER PERSEGI (CARD) --}}
                        <div class="w-56 flex-shrink-0">
                            <img src="{{ asset('posters/' . $film->poster) }}" alt="{{ $film->judul }}"
                                class="rounded-xl shadow-2xl border border-gray-800">
                        </div>

                        {{-- INFO FILM --}}
                        <div>
                            <h1 class="text-5xl font-extrabold leading-tight">
                                {{ $film->judul }}
                            </h1>

                            {{-- TAG --}}
                            <div class="flex gap-2 mt-4 text-xs">
                                <span class="px-3 py-1 rounded-full bg-emerald-600">
                                    {{ $film->genre->nama }}
                                </span>
                                <span class="px-3 py-1 rounded-full bg-gray-700">
                                    {{ $film->durasi }} Menit
                                </span>
                                <span class="px-3 py-1 rounded-full bg-gray-700">
                                    PG-13
                                </span>
                            </div>

                            {{-- HARGA --}}
                            <div class="flex items-center gap-3 mt-6">
                                <span class="text-2xl font-bold text-emerald-400">
                                    Rp {{ number_format($film->harga, 0, ',', '.') }}
                                </span>
                            </div>

                            {{-- SINOPSIS --}}
                            <p class="max-w-xl mt-4 text-gray-300 leading-relaxed">
                                {{ Str::limit($film->sinopsis, 220) }}
                            </p>

                            {{-- ACTION --}}
                            <div class="flex gap-4 mt-6">
                                <a href="#jadwal"
                                    class="flex items-center gap-2 px-6 py-3 rounded-full bg-white text-black font-semibold">
                                    ▶ Watch Trailer
                                </a>

                                <button
                                    class="flex items-center gap-2 px-6 py-3 rounded-full bg-gray-800 hover:bg-gray-700">
                                    ❤ Add to Watchlist
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- ======================
        JADWAL SECTION
    ======================= --}}
        <div id="jadwal" class="max-w-6xl mx-auto px-6 py-14" x-data="{ selectedDay: null }">

            <h2 class="text-2xl font-bold mb-6">Pilih Hari Tayang</h2>

            @php
                $days = [];
                for ($i = 0; $i < 7; $i++) {
                    $days[] = now()->addDays($i);
                }
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                @foreach ($days as $day)
                    @php $dayKey = $day->format('Y-m-d'); @endphp

                    <button @click="selectedDay = '{{ $dayKey }}'"
                        class="p-4 rounded-xl bg-gray-900 hover:bg-gray-800 border border-gray-700">
                        <p class="font-semibold">
                            {{ $day->translatedFormat('D') }}
                        </p>
                        <p class="text-sm text-gray-400">
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
                        <h3 class="text-xl font-semibold mb-4">
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

                                        @auth
                                            @if (auth()->user()->role === 'user')
                                                <a href="{{ route('user.pemesanan.kursi', $jadwal->id) }}"
                                                    class="block mt-4 text-center bg-emerald-600 hover:bg-emerald-500 py-2 rounded-lg">
                                                    Pesan Tiket
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}"
                                                class="block mt-4 text-center bg-emerald-600 hover:bg-emerald-500 py-2 rounded-lg">
                                                Login untuk Pesan
                                            </a>
                                        @endauth
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
