<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - CineMagic</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0f0f14] text-white">

<!-- NAVBAR -->
<nav class="w-full px-6 py-4 flex justify-between items-center bg-black/40 backdrop-blur">
    <h1 class="text-xl font-bold">🎬 CineMagic</h1>

    <div class="flex items-center gap-3">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="px-4 py-2 bg-red-600 rounded-lg text-sm">Logout</button>
        </form>
    </div>
</nav>

<!-- SEARCH -->
<div class="max-w-4xl mx-auto mt-6 flex justify-end">
    <input class="bg-gray-800 px-4 py-2 rounded-lg w-64" placeholder="Search film...">
</div>

<!-- ALL MOVIES -->
<div class="max-w-5xl mx-auto mt-10">
    <h2 class="text-xl font-semibold mb-4">All Movies</h2>

    <div class="grid grid-cols-4 gap-6">
        @foreach($films as $film)
        <div class="bg-gray-800 p-2 rounded-xl hover:scale-105 transition">
            <img src="{{ asset('storage/' . $film->poster) }}" class="w-full h-56 object-cover rounded-lg">
            <p class="mt-2 font-semibold">{{ $film->judul }}</p>
            <p class="text-sm text-gray-400">{{ $film->rating }}</p>

            <a href="#" class="mt-2 block bg-purple-600 text-center text-sm py-2 rounded-lg">
                View Details
            </a>
        </div>
        @endforeach
    </div>
</div>

</body>
</html>
