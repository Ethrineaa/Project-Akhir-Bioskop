<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineMagic</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white">

    {{-- NAVBAR --}}
    <nav class="bg-gray-800 py-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center px-4">
            <a href="/" class="text-2xl font-bold">CineMagic</a>

            <div class="flex gap-4">
                @auth
                    <a href="{{ route('user.dashboard') }}" class="hover:text-blue-400">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="hover:text-red-400">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-blue-400">Login</a>
                    <a href="{{ route('register') }}" class="hover:text-blue-400">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- CONTENT --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-gray-800 text-center py-5 mt-10">
        <p class="text-gray-400">© 2025 CineMagic</p>
    </footer>

</body>
</html>
