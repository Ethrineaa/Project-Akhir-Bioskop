@extends('layouts.landing')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-10">

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Poster Film -->
            <img src="{{ asset('posters/' . $film->poster) }}" class="w-60 rounded-lg shadow">

            <!-- Detail Film -->
            <div class="flex-1">
                <h1 class="text-3xl font-bold">{{ $film->judul }}</h1>

                <!-- SINOPSIS DENGAN READ MORE -->
                <div class="mt-3 text-gray-200 leading-relaxed" x-data="{ expand: false }">

                    <p class="font-semibold">Sinopsis:</p>

                    <!-- Teks penuh -->
                    <p x-show="expand" x-collapse>
                        {{ $film->sinopsis }}
                    </p>

                    <!-- Teks pendek (dipotong 180 karakter) -->
                    <p x-show="!expand" x-collapse>
                        {{ Str::limit($film->sinopsis, 180) }}
                    </p>

                    <!-- Tombol -->
                    <button @click="expand = !expand" class="mt-2 text-blue-400 hover:underline">
                        <span x-show="!expand">Read more</span>
                        <span x-show="expand">Read less</span>
                    </button>
                </div>

                <!-- Detail lainnya -->
                <div class="mt-4 space-y-1 text-gray-200">
                    <p><span class="font-semibold">Durasi:</span> {{ $film->durasi }} menit</p>
                    <p><span class="font-semibold">Harga Tiket:</span> Rp {{ number_format($film->harga, 0, ',', '.') }}</p>
                    <p>
                        <span class="font-semibold">Genre:</span>
                        {{ $film->genre->nama ?? 'Tidak ada genre' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- JADWAL PER HARI OTOMATIS -->
        <h2 class="text-2xl font-semibold mt-10">Jadwal Tayang per Hari</h2>

        @php
            // Logika generate 7 hari ke depan
            $days = [];
            for ($i = 0; $i < 7; $i++) {
                $days[] = now()->addDays($i)->format('Y-m-d');
            }
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mt-4">
            @foreach ($days as $day)
                <div class="p-4 bg-gray-800 border border-gray-700 rounded-lg shadow">
                    <h3 class="font-bold text-white">
                        {{ \Carbon\Carbon::parse($day)->translatedFormat('l, d M') }}
                    </h3>

                    @php
                        // Filter jadwal sesuai tanggal
                        $filtered = $film->jadwals->filter(function ($jadwal) use ($day) {
                            return $jadwal->tanggal == $day;
                        });
                    @endphp

                    @if ($filtered->isEmpty())
                        <p class="text-gray-500 text-sm mt-2">Tidak ada jadwal</p>
                    @else
                        @foreach ($filtered as $jadwal)
                            <div class="mt-3 p-2 bg-gray-900 rounded-lg border border-gray-700">
                                <p class="text-sm">{{ $jadwal->studio->nama }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                </p>

                                @auth
                                    @if (auth()->user()->role === 'user')
                                        <a href="{{ route('user.pesan', $jadwal->id) }}"
                                            class="mt-2 block bg-blue-600 text-white text-center text-xs px-3 py-1 rounded">
                                            Pesan Tiket
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                        class="mt-2 block bg-gray-600 text-white text-center text-xs px-3 py-1 rounded">
                                        Login untuk memesan
                                    </a>
                                @endauth
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>

    </div>
@endsection

