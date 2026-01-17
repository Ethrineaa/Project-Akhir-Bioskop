<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-900 text-white">

    <!-- ====================== NAVBAR ====================== -->
    <nav class="bg-gray-800 py-4" x-data="{ openLogin: false }" x-init="@if ($errors->any()) openLogin = true @endif">
        <div class="max-w-6xl mx-auto flex justify-between items-center px-4">

            <!-- LOGO -->
            <a href="/" class="text-2xl font-bold tracking-wide">Cinema</a>

            <!-- RIGHT ICONS -->
            <div class="flex items-center gap-3">

                @auth
                    @php
                        // ambil waktu terakhir user membuka riwayat dari DATABASE
                        $lastView = auth()->user()->last_view_payment; // ✅ GANTI BARIS INI

                        $paidUnreadCount = \App\Models\Pemesanan::where('user_id', auth()->id())
                            ->whereHas('pembayaran', function ($q) use ($lastView) {
                                $q->where('status', 'paid');
                                if ($lastView) {
                                    $q->where('updated_at', '>', $lastView);
                                }
                            })
                            ->withCount('kursi')
                            ->get()
                            ->sum('kursi_count');
                    @endphp


                    <!-- RIWAYAT -->
                    <a href="{{ route('user.pemesanan.index') }}"
                        class="relative flex items-center justify-center w-10 h-10 rounded-full bg-gray-700
                          hover:bg-blue-600 transition">

                        <i class="fa-solid fa-ticket text-lg"></i>

                        @if ($paidUnreadCount > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-green-500 text-xs w-5 h-5
                                     flex items-center justify-center rounded-full font-bold">
                                {{ $paidUnreadCount }}
                            </span>
                        @endif
                    </a>

                    <!-- USER MENU -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-700 hover:bg-blue-600 transition">
                            <i class="fa-solid fa-user text-lg"></i>
                        </button>

                        <div x-show="open" @click.away="open=false" x-transition
                            class="absolute right-0 mt-2 w-44 bg-gray-900 p-3 rounded-lg shadow-lg border border-gray-700 z-50">

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
                    <!-- ICON USER -->
                    <button @click="openLogin = true"
                        class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-700 hover:bg-blue-600 transition">
                        <i class="fa-solid fa-user text-lg"></i>
                    </button>
                @endauth

            </div>
        </div>

        <!-- ================= MODAL AUTH ================= -->
        <div x-show="openLogin" x-transition.opacity
            class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">

            <div x-data="{ tab: '{{ $errors->has('name') ? 'register' : 'login' }}' }" @click.away="openLogin = false" x-transition.scale
                class="bg-gray-900 w-96 p-6 rounded-xl shadow-lg">

                <!-- Tabs -->
                <div class="flex mb-4 border-b border-gray-700">
                    <button @click="tab='login'"
                        :class="tab == 'login' ? 'border-blue-500 text-blue-400' : 'text-gray-400'"
                        class="flex-1 pb-2 border-b-2 font-semibold">
                        Login
                    </button>
                    <button @click="tab='register'"
                        :class="tab == 'register' ? 'border-blue-500 text-blue-400' : 'text-gray-400'"
                        class="flex-1 pb-2 border-b-2 font-semibold">
                        Register
                    </button>
                </div>

                <!-- LOGIN FORM -->
                <form x-show="tab=='login'" action="{{ route('login') }}" method="POST">
                    @csrf
                    <input name="email" type="email" placeholder="Email"
                        class="w-full mb-3 px-3 py-2 rounded bg-gray-800 border border-gray-700">
                    @error('email')
                        <p class="text-red-400 text-sm mb-2">{{ $message }}</p>
                    @enderror

                    <input name="password" type="password" placeholder="Password"
                        class="w-full mb-4 px-3 py-2 rounded bg-gray-800 border border-gray-700">
                    @error('password')
                        <p class="text-red-400 text-sm mb-2">{{ $message }}</p>
                    @enderror

                    <button class="w-full py-2 rounded font-semibold bg-blue-600 hover:bg-blue-500">
                        Login
                    </button>
                </form>

                <!-- REGISTER FORM -->
                <form x-show="tab=='register'" action="{{ route('register') }}" method="POST">
                    @csrf
                    <input name="name" type="text" placeholder="Nama Lengkap"
                        class="w-full mb-3 px-3 py-2 rounded bg-gray-800 border border-gray-700">
                    @error('name')
                        <p class="text-red-400 text-sm mb-2">{{ $message }}</p>
                    @enderror

                    <input name="email" type="email" placeholder="Email"
                        class="w-full mb-3 px-3 py-2 rounded bg-gray-800 border border-gray-700">
                    @error('email')
                        <p class="text-red-400 text-sm mb-2">{{ $message }}</p>
                    @enderror

                    <input name="password" type="password" placeholder="Password"
                        class="w-full mb-3 px-3 py-2 rounded bg-gray-800 border border-gray-700">
                    @error('password')
                        <p class="text-red-400 text-sm mb-2">{{ $message }}</p>
                    @enderror

                    <input name="password_confirmation" type="password" placeholder="Confirm Password"
                        class="w-full mb-4 px-3 py-2 rounded bg-gray-800 border border-gray-700">

                    <button class="w-full py-2 rounded font-semibold bg-green-600 hover:bg-green-500">
                        Register
                    </button>
                </form>

                <button @click="openLogin=false" class="mt-3 w-full py-2 bg-gray-700 rounded hover:bg-gray-600">
                    Tutup
                </button>
            </div>
        </div>
    </nav>

    <!-- ================= CONTENT ================= -->
    <main class="min-h-screen">
        @yield('content')
    </main>

</body>

</html>
