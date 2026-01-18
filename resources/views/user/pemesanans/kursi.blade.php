@extends('layouts.landing')

@section('content')
<div class="min-h-screen bg-gray-900 text-gray-100 px-6 py-10">
    <div class="max-w-5xl mx-auto">

        <h2 class="text-2xl font-bold mb-6">
            Layout Kursi — {{ $pemesanan->jadwal->film->judul }}
        </h2>

        {{-- INFO --}}
        <div class="mb-6 text-sm text-gray-300 space-y-1">
            <p><span class="text-gray-400">Studio :</span> {{ $pemesanan->jadwal->studio->nama }}</p>
            <p><span class="text-gray-400">Jam :</span> {{ $pemesanan->jadwal->jam }}</p>
            <p><span class="text-gray-400">Jumlah Tiket :</span> {{ $pemesanan->jumlah_tiket }}</p>
        </div>

        {{-- SCREEN --}}
        <div class="mb-12 text-center">
            <div class="bg-gradient-to-r from-transparent via-gray-400 to-transparent
                h-1.5 w-3/4 mx-auto rounded-full"></div>
            <p class="text-xs tracking-widest text-gray-400 mt-3">SCREEN</p>
        </div>

        {{-- SORT KURSI --}}
        @php
            $kursiSorted = $kursi->sortBy(function ($item) {
                preg_match('/([A-Z]+)(\d+)/', $item->nomor_kursi, $match);
                return $match[1] . str_pad($match[2], 3, '0', STR_PAD_LEFT);
            })->values();
        @endphp

        {{-- GRID KURSI --}}
        <div class="flex justify-center">
            <div class="grid grid-cols-[repeat(5,_3rem)_3.5rem_repeat(5,_3rem)] gap-x-3 gap-y-5">

                @foreach ($kursiSorted as $index => $item)
                    @php
                        $col = ($index % 10) + 1;
                        $gridCol = $col > 5 ? $col + 1 : $col;

                        // kursi yang dipesan di pemesanan ini
                        $isMine = in_array($item->id, $kursiPemesanan);

                        // kursi yang sudah dipesan orang lain
                        $isBooked = in_array($item->id, $kursiTerpesan);
                    @endphp

                    <div style="grid-column: {{ $gridCol }};"
                        class="w-12 h-12 rounded-lg text-sm font-semibold flex items-center justify-center
                        {{
                            $isMine ? 'bg-blue-600 text-white' :
                            ($isBooked ? 'bg-blue-300 text-blue-900' :
                            'bg-gray-700 text-gray-200')
                        }}">
                        {{ $item->nomor_kursi }}
                    </div>
                @endforeach

            </div>
        </div>

        {{-- LEGEND --}}
        <div class="flex gap-8 mt-10 text-sm justify-center">
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 bg-gray-700 rounded"></span> Tersedia
            </div>
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 bg-blue-600 rounded"></span> Kursi Saya
            </div>
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 bg-blue-300 rounded"></span> Sudah Dipesan
            </div>
        </div>

        {{-- BACK BUTTON --}}
        <div class="text-center mt-10">
            <a href="{{ route('user.pemesanan.show', $pemesanan->id) }}"
               class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-lg font-semibold">
                Kembali ke Detail Tiket
            </a>
        </div>

    </div>
</div>
@endsection
