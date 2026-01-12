<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema</title>

    <!-- IMPORTANT: CSRF TOKEN -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- IMPORTANT: BASE URL -->
    <meta name="base-url" content="{{ url('/') }}">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-gray-900 text-white">

<!-- ====================== NAVBAR ====================== -->
<nav class="bg-gray-800 py-4" x-data="{ openLogin: false }">
    <div class="max-w-6xl mx-auto flex justify-between items-center px-4">

        <!-- LOGO -->
        <a href="/" class="text-2xl font-bold tracking-wide">Cinema</a>

        <!-- RIGHT ICONS -->
        <div class="flex items-center gap-3">

            @auth
                @php
                    $pendingCount = \App\Models\Pemesanan::where('user_id', auth()->id())
                        ->whereHas('pembayaran', function ($q) {
                            $q->whereIn('status', ['waiting', 'pending']);
                        })->count();
                @endphp

                <!-- RIWAYAT -->
                <a href="{{ route('user.pemesanan.index') }}"
                   class="relative flex items-center justify-center w-10 h-10 rounded-full bg-gray-700
                          hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/40 transition">

                    <i class="fa-solid fa-ticket text-lg"></i>

                    @if($pendingCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-xs w-5 h-5
                                     flex items-center justify-center rounded-full font-bold">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <!-- USER MENU -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-700
                               hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/40 transition">
                        <i class="fa-solid fa-user text-lg"></i>
                    </button>

                    <div x-show="open" @click.away="open=false" x-transition
                        class="absolute right-0 mt-2 w-44 bg-gray-900 p-3 rounded-lg
                               shadow-lg border border-gray-700 z-50">

                        <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
                        <hr class="my-2 border-gray-700">

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-red-400 hover:text-red-300 text-sm">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

            @else
                <!-- ICON TIKET -->
                <a href="{{ route('login') }}"
                   class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-700
                          hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/40 transition">
                    <i class="fa-solid fa-ticket text-lg"></i>
                </a>

                <!-- ICON USER -->
                <button @click="openLogin = true"
                    class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-700
                           hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/40 transition">
                    <i class="fa-solid fa-user text-lg"></i>
                </button>
            @endauth
        </div>
    </div>

    <!-- ================= MODAL LOGIN ================= -->
    <div x-show="openLogin" x-transition.opacity
        class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">

        <div @click.away="openLogin = false" x-transition.scale
            class="bg-gray-900 w-80 p-6 rounded-xl shadow-lg">

            <h2 class="text-xl font-bold mb-4">Login</h2>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <label class="text-sm">Email</label>
                <input type="email" name="email"
                    class="w-full mt-1 mb-3 px-3 py-2 rounded bg-gray-800 border border-gray-700 focus:border-blue-500 outline-none"
                    required>

                <label class="text-sm">Password</label>
                <input type="password" name="password"
                    class="w-full mt-1 mb-4 px-3 py-2 rounded bg-gray-800 border border-gray-700 focus:border-blue-500 outline-none"
                    required>

                <button class="w-full py-2 bg-blue-600 rounded-lg hover:bg-blue-500 transition font-semibold">
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

<!-- ================= CONTENT ================= -->
<main class="min-h-screen">
    @yield('content')
</main>

<!-- ================= FOOTER ================= -->
<footer class="bg-gray-800 text-center py-5 mt-10">
    <p class="text-gray-400">© 2026 Cinema</p>
</footer>

</body>
</html>
