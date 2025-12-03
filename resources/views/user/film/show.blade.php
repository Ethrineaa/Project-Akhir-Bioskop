@extends('layouts.landing')

@section('content')
<div class="max-w-5xl mx-auto mt-10 text-white">

    <div class="flex gap-6">
        <img src="{{ asset('posters/' . $film->poster) }}"
             class="w-60 h-80 object-cover rounded-xl">

        <div>
            <h1 class="text-3xl font-bold">{{ $film->judul }}</h1>
            <p class="text-gray-400 mt-1">{{ $film->genre->nama }}</p>

            <p class="mt-4 text-gray-300">
                {{ $film->deskripsi ?? 'Tidak ada deskripsi.' }}
            </p>
        </div>
    </div>

    <h2 class="text-xl font-semibold mt-8 mb-3">Jadwal Tayang</h2>

    <div class="space-y-3">
        @forelse ($film->jadwals as $jadwal)
            <div class="bg-gray-800 p-4 rounded-lg flex justify-between items-center">

                <div>
                    <p class="font-semibold">{{ $jadwal->tanggal }}</p>
                    <p class="text-gray-400">{{ $jadwal->jam }}</p>
                </div>

                @auth
                    <!-- User login → bisa pesan -->
                    @if (Auth::user()->role === 'user')
                        <a href="{{ route('user.pemesanan.create', ['jadwal' => $jadwal->id]) }}">
                            <button class="px-4 py-2 bg-purple-600 rounded-lg hover:bg-purple-500">
                                Pesan Tiket
                            </button>
                        </a>
                    @endif

                    <!-- Admin tidak bisa pesan -->
                    @if (Auth::user()->role === 'admin')
                        <span class="text-gray-400 text-sm">Admin tidak bisa memesan tiket</span>
                    @endif
                @endauth

                @guest
                    <!-- Guest → diarahkan ke login -->
                    <a href="{{ route('login') }}">
                        <button class="px-4 py-2 bg-purple-600 rounded-lg hover:bg-purple-500">
                            Login untuk Memesan
                        </button>
                    </a>
                @endguest
            </div>

        @empty
            <p class="text-gray-400">Tidak ada jadwal tersedia.</p>
        @endforelse
    </div>

</div>
@endsection
