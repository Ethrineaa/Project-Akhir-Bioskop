<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="bg-gray-900 text-white">

    <!-- ====================== NAVBAR ====================== -->
    <nav class="bg-gray-800 py-4" x-data="{ openLogin: false }">
        <div class="max-w-6xl mx-auto flex justify-between items-center px-4">
            <a href="/" class="text-2xl font-bold">Cinema</a>

            <div class="flex items-center gap-4">

                @auth
                    <!-- =================== TICKET HISTORY ICON =================== -->
                    <div class="relative" x-data="{ openTicket: false }">
                        @php
                            $pendingCount = \App\Models\Pemesanan::where('user_id', auth()->id())
                                ->whereHas('pembayaran', function ($q) {
                                    $q->whereIn('status', ['waiting', 'pending']);
                                })
                                ->count();
                        @endphp
                        <a href="{{ route('user.pemesanan.index') }}"
                            class="p-2 bg-gray-700 rounded-full hover:bg-gray-600 transition relative"
                            title="Riwayat Pemesanan">
                            <!-- Heroicon Ticket -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 2H15C16.1046 2 17 2.89543 17 4V6.382C16.407 6.14 15.722 6 15 6C13.3431 6 12 7.34315 12 9C12 10.6569 13.3431 12 15 12C15.722 12 16.407 11.86 17 11.618V20C17 21.1046 16.1046 22 15 22H9C7.89543 22 7 21.1046 7 20V17.382C7.593 17.624 8.278 17.764 9 17.764C10.6569 17.764 12 16.421 12 14.764C12 13.1071 10.6569 11.764 9 11.764C8.278 11.764 7.593 11.904 7 12.146V4C7 2.89543 7.89543 2 9 2Z" />
                            </svg>

                            <!-- Badge Jumlah Pemesanan Pending -->
                            @if ($pendingCount > 0)
                                <span
                                    class="absolute -top-1 -right-1 bg-red-500 text-xs w-5 h-5 flex items-center justify-center rounded-full font-semibold">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                    </div>

                    <!-- =================== USER DROPDOWN =================== -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2 bg-gray-700 rounded-full hover:bg-gray-600 transition">
                            <!-- Heroicon User Circle -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.121 17.804A9 9 0 1 1 18 18M15 14a3 3 0 1 0-6 0" />
                            </svg>
                        </button>

                        <!-- DROPDOWN -->
                        <div x-show="open" @click.away="open=false" x-transition
                            class="absolute right-0 mt-2 w-44 bg-gray-900 p-3 rounded-lg shadow-lg">
                            <p class="font-semibold">{{ Auth::user()->name }}</p>
                            <hr class="my-2 border-gray-700">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="mt-2 text-red-400 hover:text-red-300">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- =================== TICKET ICON UNTUK BELUM LOGIN =================== -->
                    <a href="{{ route('login') }}"
                        class="p-2 bg-gray-700 rounded-full hover:bg-gray-600 transition relative"
                        title="Login untuk melihat riwayat">
                        <!-- Heroicon Ticket -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 2H15C16.1046 2 17 2.89543 17 4V6.382C16.407 6.14 15.722 6 15 6C13.3431 6 12 7.34315 12 9C12 10.6569 13.3431 12 15 12C15.722 12 16.407 11.86 17 11.618V20C17 21.1046 16.1046 22 15 22H9C7.89543 22 7 21.1046 7 20V17.382C7.593 17.624 8.278 17.764 9 17.764C10.6569 17.764 12 16.421 12 14.764C12 13.1071 10.6569 11.764 9 11.764C8.278 11.764 7.593 11.904 7 12.146V4C7 2.89543 7.89543 2 9 2Z" />
                        </svg>
                    </a>

                    <!-- =================== USER ICON BUKA LOGIN MODAL =================== -->
                    <button @click="openLogin = true" class="p-2 bg-gray-700 rounded-full hover:bg-gray-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 110 8 4 4 0 010-8zm-7 14a7 7 0 1114 0H3z"
                                clip-rule="evenodd" />
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
