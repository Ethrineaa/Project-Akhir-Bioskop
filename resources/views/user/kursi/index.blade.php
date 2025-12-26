@extends('layouts.landing')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-10 text-white">

        {{-- HEADER --}}
        <div class="mb-8">
            <h1 class="text-2xl font-semibold">{{ $jadwal->film->judul }}</h1>
            <p class="text-sm text-gray-400">
                {{ $jadwal->film->genre->nama }} • Studio {{ $jadwal->studio->nama }} •
                {{ \Carbon\Carbon::parse($jadwal->jam)->format('H:i') }}
            </p>
        </div>

        {{-- SEAT CONTAINER --}}
        <div class="bg-[#0B1220] rounded-2xl p-8">

            {{-- SCREEN --}}
            <div class="flex flex-col items-center mb-10">
                <div class="w-72 h-2 bg-gradient-to-r from-transparent via-blue-500 to-transparent rounded-full mb-2"></div>
                <p class="text-xs tracking-widest text-gray-400">SCREEN</p>
            </div>

            {{-- SEATS --}}
            @php
                $groupedSeats = $kursi
                    ->groupBy(fn($item) => substr($item->nomor_kursi, 0, 1))
                    ->map(fn($seats) => $seats->sortBy(fn($seat) => intval(substr($seat->nomor_kursi, 1))));
            @endphp

            <div class="space-y-4 flex flex-col items-center">
                @foreach ($groupedSeats as $row => $seats)
                    <div class="flex items-center gap-3">
                        <span class="w-4 text-gray-500 text-sm">{{ $row }}</span>

                        <div class="grid grid-cols-{{ $seats->count() }} gap-3">
                            @foreach ($seats as $item)
                                <button type="button"
                                    class="seat w-8 h-8 rounded-md bg-gray-700
                                        hover:bg-blue-600 transition text-xs"
                                    data-seat="{{ $item->nomor_kursi }}">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- LEGEND --}}
            <div class="flex justify-center gap-6 text-xs mt-10 text-gray-400">
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-gray-700 rounded"></span> Available
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-blue-600 rounded"></span> Selected
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-gray-500 rounded opacity-40"></span> Booked
                </div>
            </div>
        </div>

        {{-- BOTTOM SUMMARY --}}
        <div class="mt-8 bg-[#0F172A] rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">

            <div>
                <p class="text-sm text-gray-400">Selected Seats</p>
                <p id="seatList" class="font-medium">None</p>
            </div>

            <div class="flex items-center gap-6">
                <div>
                    <p class="text-xs text-gray-400">Total</p>
                    <p class="text-lg font-semibold text-blue-500" id="grandTotal">Rp 0</p>
                </div>

                <form action="{{ route('user.pemesanan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="seatInput" name="seats">
                    <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

                    <button class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-xl font-semibold">
                        Buy Tickets →
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection
