@extends('layouts.landing')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-10" x-data="{ selectedDay: null, openLogin: false }">

        {{-- DETAIL FILM --}}
        <div class="flex flex-col md:flex-row gap-6">
            <img src="{{ asset('posters/' . $film->poster) }}" class="w-60 rounded-lg shadow">

            <div class="flex-1">
                <h1 class="text-3xl font-bold">{{ $film->judul }}</h1>

                {{-- SINOPSIS --}}
                <div class="mt-3 text-gray-200 leading-relaxed" x-data="{ expand: false }">
                    <p class="font-semibold">Sinopsis:</p>
                    <p x-show="expand" x-collapse>{{ $film->sinopsis }}</p>
                    <p x-show="!expand" x-collapse>{{ Str::limit($film->sinopsis, 180) }}</p>

                    <button @click="expand = !expand" class="mt-2 text-blue-400 hover:underline">
                        <span x-show="!expand">Read more</span>
                        <span x-show="expand">Read less</span>
                    </button>
                </div>

                <div class="mt-4 space-y-1 text-gray-200">
                    <p><span class="font-semibold">Durasi:</span> {{ $film->durasi }} menit</p>
                    <p><span class="font-semibold">Harga Tiket:</span> Rp {{ number_format($film->harga, 0, ',', '.') }}</p>
                    <p><span class="font-semibold">Genre:</span> {{ $film->genre->nama }}</p>
                </div>
            </div>
        </div>

        {{-- FILTER HARI --}}
        <h2 class="text-2xl font-semibold mt-10">Pilih Hari Tayang</h2>

        @php
            $days = [];
            for ($i = 0; $i < 7; $i++) {
                $days[] = now()->addDays($i);
            }
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mt-4">
            @foreach ($days as $day)
                @php
                    $dayKey = $day->format('Y-m-d');
                @endphp

                <div @click="selectedDay === '{{ $dayKey }}' ? selectedDay = null : selectedDay = '{{ $dayKey }}'"
                    class="cursor-pointer p-4 bg-gray-800 border border-gray-700 hover:bg-gray-700 rounded-lg shadow transition">
                    <h3 class="font-bold text-white">
                        {{ $day->translatedFormat('l, d M') }}
                    </h3>
                </div>
            @endforeach
        </div>

        {{-- RESULT: JADWAL --}}
        <div class="mt-6" x-show="selectedDay" x-collapse>
            @foreach ($days as $day)
                @php
                    $dayKey = $day->format('Y-m-d');
                    $filtered = $film->jadwals->filter(fn($j) => $j->tanggal == $dayKey);
                @endphp

                <div x-show="selectedDay === '{{ $dayKey }}'" x-collapse>

                    <h3 class="text-xl font-semibold mb-3">
                        Jadwal untuk {{ $day->translatedFormat('l, d M') }}
                    </h3>

                    {{-- Jika tidak ada jadwal --}}
                    @if ($filtered->isEmpty())
                        <p class="text-gray-400">Tidak ada jadwal hari ini</p>
                    @else
                        {{-- GRID JADWAL (sebelumnya column, sekarang horizontal) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

                            @foreach ($filtered as $jadwal)
                                <div class="p-4 bg-gray-900 rounded-lg border border-gray-700 shadow flex flex-col gap-1">

                                    <p class="font-semibold">{{ $jadwal->studio->nama }}</p>

                                    <p class="text-gray-400 text-sm">
                                        {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                    </p>

                                    {{-- Kursi --}}
                                    @php
                                        $total = $jadwal->studio->kursis->count();
                                        $booked = $jadwal->pemesanan()->count();
                                        $available = $total - $booked;
                                    @endphp

                                    <p class="text-sm mt-1">
                                        Kursi tersedia:
                                        <span class="font-bold text-green-400">{{ $available }}</span>
                                        / {{ $total }}
                                    </p>

                                    {{-- TOMBOL PESAN --}}
                                    @auth
                                        @if (auth()->user()->role === 'user')
                                            <a href="{{ route('user.pesan', $jadwal->id) }}"
                                                class="mt-2 inline-block bg-blue-600 text-white px-4 py-1 rounded text-sm">
                                                Pesan Tiket
                                            </a>
                                        @endif
                                    @else
                                        <button @click="openLogin = true"
                                            class="mt-2 inline-block bg-blue-600 text-white px-4 py-1 rounded text-sm">
                                            Pesan Tiket
                                        </button>
                                    @endauth

                                </div>
                            @endforeach

                        </div>
                    @endif

                </div>
            @endforeach
        </div>

        {{-- MODAL LOGIN --}}
        <div x-show="openLogin" x-transition.opacity
            class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
            <div class="bg-gray-800 w-full max-w-sm p-6 rounded-lg shadow-lg border border-gray-700" x-transition.scale>
                <h2 class="text-xl font-bold mb-4 text-center">Login Dulu</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="text-sm">Email</label>
                        <input type="email" name="email" class="w-full p-2 rounded bg-gray-900 border border-gray-700">
                    </div>

                    <div class="mb-4">
                        <label class="text-sm">Password</label>
                        <input type="password" name="password"
                            class="w-full p-2 rounded bg-gray-900 border border-gray-700">
                    </div>

                    <button class="w-full bg-blue-600 py-2 rounded">Login</button>
                </form>

                <button @click="openLogin=false" class="mt-3 text-center w-full text-gray-400 hover:text-white">
                    Batal
                </button>
            </div>
        </div>

    </div>
@endsection
