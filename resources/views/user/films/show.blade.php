@extends('layouts.landing')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">

    <div class="flex gap-6">
        <!-- Poster Film -->
        <img src="{{ asset('storage/' . $film->poster) }}"
             class="w-60 rounded-lg shadow">

        <!-- Detail Film -->
        <div>
            <h1 class="text-3xl font-bold">{{ $film->judul }}</h1>
            <p class="mt-3 text-gray-700">{{ $film->deskripsi }}</p>
        </div>
    </div>

    <h2 class="text-2xl font-semibold mt-10">Jadwal Tersedia</h2>

    @if($film->jadwals->isEmpty())
        <p class="text-gray-500 mt-2">Tidak ada jadwal tersedia.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            @foreach ($film->jadwals as $jadwal)
                <div class="p-4 border rounded-lg shadow">
                    <h3 class="font-bold">{{ $jadwal->studio->nama }}</h3>
                    <p class="text-gray-600 mt-1">{{ $jadwal->tanggal }}</p>
                    <p class="text-gray-600">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</p>

                    @auth
                        @if(auth()->user()->role === 'user')
                            <a href="#" class="mt-3 inline-block bg-blue-600 text-white px-4 py-2 rounded">
                                Pesan Tiket
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="mt-3 inline-block bg-gray-600 text-white px-4 py-2 rounded">
                            Login untuk memesan
                        </a>
                    @endauth
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
