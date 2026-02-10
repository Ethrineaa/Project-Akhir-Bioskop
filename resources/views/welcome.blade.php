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
            <button class="px-4 py-1 rounded-full
                        {{ request('genre') ? 'bg-gray-700' : 'bg-purple-600' }}">
                All
            </button>
        </a>

        <!-- Genre Loop -->
        @foreach ($genres as $genre)
            <a href="?genre={{ $genre->id }}">
                <button class="px-4 py-1 rounded-full
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
                <div class="col-span-4 flex flex-col items-center justify-center py-16 text-gray-400">
                    <i class="fa-solid fa-clapperboard text-6xl mb-4"></i>

                    @if (request('genre'))
                        <p class="text-lg font-semibold">Belum ada film di genre ini</p>
                        <p class="text-sm">Coba pilih genre lain</p>
                    @else
                        <p class="text-lg font-semibold">Belum ada film ditambahkan</p>
                        <p class="text-sm">Film akan tampil di sini setelah admin menambahkannya</p>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    <div class="pb-5"></div>

    <!-- CHAT POPUP -->
    <!-- CHAT POPUP (Guest & Auth) -->
    <div id="chat-popup"
        class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-2 w-full max-w-sm pointer-events-none">

        <!-- Chat Box -->
        <div id="chat-box"
            class="w-80 bg-gray-900 border border-gray-700 rounded-2xl shadow-2xl flex flex-col h-96 overflow-hidden hidden transition-all duration-300 transform origin-bottom-right scale-95 opacity-0 pointer-events-auto">
            <!-- Header -->
            <div class="bg-gray-800 border-b border-gray-700 px-5 py-4 flex justify-between items-center text-white">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-tr from-purple-500 to-indigo-500 flex items-center justify-center text-xs font-bold shadow-sm">
                            CS
                        </div>
                        <span
                            class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-gray-800 rounded-full"></span>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-gray-100">Customer Support</h4>
                        <p class="text-xs text-green-400">Online</p>
                    </div>
                </div>
                <button id="chat-close" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fa-solid fa-times text-lg"></i>
                </button>
            </div>

            <!-- Messages Area -->
            <div id="chat-messages"
                class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-900 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent">
                @auth
                    @forelse($chats as $chat)
                        @if($chat->sender_type === 'user')
                            <div class="flex justify-end animate-fade-in-up">
                                <div class="bg-purple-600 text-white px-4 py-2 rounded-2xl rounded-tr-sm max-w-[85%] text-sm shadow-md">
                                    {{ $chat->message }}
                                </div>
                            </div>
                        @else
                            <div class="flex justify-start animate-fade-in-up">
                                <div
                                    class="bg-gray-800 text-gray-200 px-4 py-2 rounded-2xl rounded-tl-sm max-w-[85%] text-sm shadow-md border border-gray-700">
                                    {{ $chat->message }}
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-gray-500 space-y-2 opacity-70">
                            <i class="fa-regular fa-comments text-4xl text-gray-600"></i>
                            <p class="text-xs">Start a conversation!</p>
                        </div>
                    @endforelse
                @else
                    <div class="flex flex-col items-center justify-center h-full text-center px-6 space-y-4">
                        <div
                            class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center text-purple-500 mb-2 border border-gray-700">
                            <i class="fa-solid fa-lock text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-white font-bold text-base">Login Required</p>
                            <p class="text-gray-400 text-sm mt-1">Please login to access our support chat.</p>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Footer / Input Area -->
            <div class="p-3 bg-gray-800 border-t border-gray-700">
                @auth
                    <form action="{{ route('user.chat.store') }}" method="POST" class="flex gap-2 items-center" id="chat-form">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        <input type="text" name="message"
                            class="flex-1 bg-gray-900 border border-gray-700 text-white text-sm rounded-full px-4 py-3 focus:ring-1 focus:ring-purple-500 placeholder-gray-500 transition-all outline-none"
                            placeholder="Type your message..." required autocomplete="off">
                        <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white p-3 rounded-full transition-all shadow-lg active:scale-95 flex items-center justify-center w-10 h-10">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                        </button>
                    </form>
                @else
                    <div class="text-center">
                        <a href="{{ route('login') }}"
                            class="flex items-center justify-center gap-2 w-full bg-gray-700 hover:bg-gray-600 text-white text-sm font-bold py-3 rounded-full shadow-lg transition-all hover:bg-gray-500 group border border-gray-600">
                            <span>Login to Chat</span>
                            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Toggle Button -->
        <button id="chat-toggle"
            class="bg-purple-600 hover:bg-purple-700 text-white w-14 h-14 rounded-full shadow-[0_4px_14px_0_rgba(147,51,234,0.39)] flex items-center justify-center transition-all duration-300 transform hover:scale-110 pointer-events-auto">
            <i class="fa-regular fa-comment-dots text-2xl"></i>
        </button>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chatToggle = document.getElementById('chat-toggle');
            const chatBox = document.getElementById('chat-box');
            const chatClose = document.getElementById('chat-close');
            const chatMessages = document.getElementById('chat-messages');

            // Function to scroll to bottom
            const scrollToBottom = () => {
                if (chatMessages) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            };

            // Toggle Chat Visibility
            if (chatToggle && chatBox) {
                chatToggle.addEventListener('click', () => {
                    const isHidden = chatBox.classList.contains('hidden');

                    if (isHidden) {
                        // Open Chat
                        chatBox.classList.remove('hidden');
                        // Small delay to allow display:block to apply before opacity transition
                        requestAnimationFrame(() => {
                            chatBox.classList.remove('opacity-0', 'scale-95');
                            chatBox.classList.add('opacity-100', 'scale-100');
                            scrollToBottom();
                        });
                    } else {
                        // Close Chat (Fallback if toggle button used to close)
                        chatBox.classList.remove('opacity-100', 'scale-100');
                        chatBox.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            chatBox.classList.add('hidden');
                        }, 300);
                    }
                });
            }

            // Close Button Event
            if (chatClose && chatBox) {
                chatClose.addEventListener('click', (e) => {
                    e.preventDefault();
                    chatBox.classList.remove('opacity-100', 'scale-100');
                    chatBox.classList.add('opacity-0', 'scale-95');

                    setTimeout(() => {
                        chatBox.classList.add('hidden');
                    }, 300); // Matches transition duration
                });
            }

            // Scroll on load (in case it was somehow open or for init)
            scrollToBottom();
        });
    </script>
@endsection