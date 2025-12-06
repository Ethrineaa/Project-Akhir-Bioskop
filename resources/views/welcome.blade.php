@extends('layouts.landing')

@section('content')

<!-- BANNER -->
<div class="w-full h-[300px] bg-cover bg-center mt-4 rounded-xl mx-auto max-w-5xl"
    style="background-image: url('https://images.unsplash.com/photo-1524985069026-dd778a71c7b4');">
</div>

<!-- FILTER GENRE -->
<div class="max-w-5xl mx-auto mt-6 flex gap-3 overflow-x-auto pb-2">

    <!-- All -->
    <a href="{{ route('landing') }}">
        <button
            class="px-4 py-1 rounded-full
            {{ request('genre') ? 'bg-gray-700' : 'bg-purple-600' }}">
            All
        </button>
    </a>

    <!-- Genre Loop -->
    @foreach ($genres as $genre)
        <a href="?genre={{ $genre->id }}">
            <button
                class="px-4 py-1 rounded-full
                {{ request('genre') == $genre->id ? 'bg-purple-600' : 'bg-gray-700' }}">
                {{ $genre->nama }}
            </button>
        </a>
    @endforeach

</div>

<!-- NOW SHOWING -->
<div class="max-w-5xl mx-auto mt-10">
    <h2 class="text-xl font-semibold mb-4">
        {{ request('genre') ? 'Filtered Movies' : 'Now Showing' }}
    </h2>

    <div class="grid grid-cols-4 gap-6">
        @forelse ($films as $film)
            <div class="bg-gray-800 p-2 rounded-xl hover:scale-105 transition">

                <a href="{{ route('film.show', $film->id) }}">
                    <img src="{{ asset('posters/' . $film->poster) }}"
                        class="w-full h-56 object-cover rounded-lg cursor-pointer hover:opacity-80 transition">
                </a>

                <p class="mt-2 font-semibold">{{ $film->judul }}</p>
                <p class="text-sm text-gray-400">
                    {{ $film->genre->nama ?? 'Unknown' }}
                </p>
            </div>
        @empty
            <p class="text-gray-400">Tidak ada film pada genre ini.</p>
        @endforelse
    </div>
</div>
@endsection
