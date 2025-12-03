<!DOCTYPE html>
<html lang="en" x-data="{ openLogin: false }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineMagic</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-[#0f0f14] text-white">

    <!-- NAVBAR -->
    <nav class="w-full px-6 py-4 flex justify-between items-center bg-black/40 backdrop-blur">
        <h1 class="text-xl font-bold">🎬 CineMagic</h1>

        <div class="flex items-center gap-4">

            <!-- Guest -->
            @guest
                <button @click="openLogin = true" class="p-2 rounded-full bg-gray-700 hover:bg-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" class="w-6 h-6">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4
                    0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4
                    6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516
                    10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168
                    1.332-.678.678-.83 1.418-.832 1.664h10z" />
                    </svg>
                </button>
            @endguest

            <!-- Auth -->
            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-2 rounded-full bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-7 h-7" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6
                        3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4
                        0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1
                        1H3s-1 0-1-1 1-4 6-4 6 3 6
                        4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516
                        10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168
                        1.332-.678.678-.83 1.418-.832 1.664h10z" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open=false"
                        class="absolute right-0 mt-2 w-40 bg-gray-800 p-3 rounded-lg shadow-lg">
                        <p class="font-semibold">{{ Auth::user()->name }}</p>
                        <hr class="my-2 border-gray-600">

                        <a href="#" class="block py-1 hover:text-purple-400">My Tickets</a>
                        <a href="#" class="block py-1 hover:text-purple-400">Profile</a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="mt-2 text-red-400 hover:text-red-300">Logout</button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </nav>

    <!-- LOGIN MODAL -->
    <div x-show="openLogin" x-transition.opacity.duration.300ms
        class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">

        <div class="bg-[#1a1a1f] p-6 rounded-xl w-80 shadow-lg"
            x-transition:enter="transform transition duration-300 ease-out"
            x-transition:enter-start="opacity-0 scale-50 rotate-6"
            x-transition:enter-end="opacity-100 scale-100 rotate-0"
            x-transition:leave="transform transition duration-200 ease-in"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-75">

            <h2 class="text-xl font-bold mb-4">Login to CineMagic</h2>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <label class="text-sm text-gray-300">Email</label>
                <input type="email" name="email" required
                    class="w-full mt-1 mb-3 px-3 py-2 rounded bg-gray-800 border border-gray-700">

                <label class="text-sm text-gray-300">Password</label>
                <input type="password" name="password" required
                    class="w-full mt-1 mb-4 px-3 py-2 rounded bg-gray-800 border border-gray-700">

                <button class="w-full py-2 bg-purple-600 rounded-lg hover:bg-purple-500 transition">
                    Login
                </button>
            </form>

            <p class="text-center text-sm text-gray-400 mt-3">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-purple-400 hover:underline">Register</a>
            </p>

            <button @click="openLogin = false"
                class="mt-4 w-full py-2 bg-gray-700 rounded-lg hover:bg-gray-600 transition">
                Close
            </button>
        </div>
    </div>

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

                    <!-- poster dari public/posters -->
                    <img src="{{ asset('posters/' . $film->poster) }}" class="w-full h-56 object-cover rounded-lg">

                    <p class="mt-2 font-semibold">{{ $film->judul }}</p>

                    <!-- tampilkan nama genre -->
                    <p class="text-sm text-gray-400">
                        {{ $film->genre->nama ?? 'Unknown' }}
                    </p>

                </div>
            @empty
                <p class="text-gray-400">Tidak ada film pada genre ini.</p>
            @endforelse
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="text-center mt-16 py-6 text-gray-400 text-sm">
        ©2024 CineMagic. All rights reserved.
    </footer>

</body>

</html>
