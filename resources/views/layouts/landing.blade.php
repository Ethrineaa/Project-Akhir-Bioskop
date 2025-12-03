<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CineMagic</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-900 text-white">

    <!-- ====================== NAVBAR ====================== -->
    <nav class="bg-gray-800 py-4" x-data="{ openLogin: false }">
        <div class="max-w-6xl mx-auto flex justify-between items-center px-4">
            <a href="/" class="text-2xl font-bold">CineMagic</a>

            <div class="flex items-center gap-4">

                @auth
                    <!-- =================== USER DROPDOWN =================== -->
                    <div class="relative" x-data="{ open: false }">

                        <!-- ICON -->
                        <button @click="open = !open" class="p-2 bg-gray-700 rounded-full hover:bg-gray-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm4 8c0 1-1 1-1 1H5s-1 0-1-1 1-4 4-4 4 3 4 4z" />
                            </svg>
                        </button>

                        <!-- DROPDOWN -->
                        <div x-show="open" @click.away="open=false" x-transition
                            class="absolute right-0 mt-2 w-44 bg-gray-900 p-3 rounded-lg shadow-lg">

                            <p class="font-semibold">{{ Auth::user()->name }}</p>
                            <hr class="my-2 border-gray-700">

                            <a href="{{ route('user.dashboard') }}" class="block py-1 hover:text-blue-400">
                                Dashboard
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="mt-2 text-red-400 hover:text-red-300">Logout</button>
                            </form>

                        </div>
                    </div>
                @else
                    <!-- =================== IKON USER (LOGIN MODAL) =================== -->
                    <button @click="openLogin = true" class="p-2 bg-gray-700 rounded-full hover:bg-gray-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm4 8c0 1-1 1-1 1H5s-1 0-1-1 1-4 4-4 4 3 4 4z" />
                        </svg>
                    </button>
                @endauth

            </div>
        </div>

        <!-- ====================== MODAL LOGIN ====================== -->
        <div x-show="openLogin" x-transition.opacity
            class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">

            <div @click.away="openLogin = false" x-transition.scale.origin.top.duration.300ms
                class="bg-gray-900 w-80 p-6 rounded-xl shadow-lg">

                <h2 class="text-xl font-bold mb-4">Login</h2>

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <label>Email</label>
                    <input type="email" name="email"
                        class="w-full mt-1 mb-3 px-3 py-2 rounded bg-gray-800 border border-gray-700" required>

                    <label>Password</label>
                    <input type="password" name="password"
                        class="w-full mt-1 mb-4 px-3 py-2 rounded bg-gray-800 border border-gray-700" required>

                    <button class="w-full py-2 bg-blue-600 rounded-lg hover:bg-blue-500 transition">
                        Login
                    </button>
                </form>

                <p class="text-sm mt-4 text-gray-400 text-center">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-400 hover:underline">Register</a>
                </p>

                <button @click="openLogin = false"
                    class="mt-4 w-full py-2 bg-gray-700 rounded-lg hover:bg-gray-600 transition">
                    Tutup
                </button>
            </div>
        </div>
    </nav>




    <!-- ====================== CONTENT ====================== -->
    <main class="min-h-screen">
        @yield('content')
    </main>




    <!-- ====================== FOOTER ====================== -->
    <footer class="bg-gray-800 text-center py-5 mt-10">
        <p class="text-gray-400">© 2025 CineMagic</p>
    </footer>

</body>

</html>
