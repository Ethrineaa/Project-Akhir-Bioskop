@extends('admin.layouts.app')

@section('content')
    <!-- Inject Tailwind & FontAwesome locally for this page -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Override Tailwind Preflight collisions with Bootstrap if necessary -->
    <style>
        /* Revert Bootstrap container padding if Tailwind interferes, or vice versa */
        .chat-container a { text-decoration: none; }
    </style>

    <div class="chat-container h-[calc(100vh-10rem)] flex bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 mt-2">
        <!-- SIDEBAR: LIST USER -->
        <div class="w-1/3 bg-gray-50 border-r border-gray-200 flex flex-col">
            <div class="p-4 bg-white border-b border-gray-200 shadow-sm sticky top-0 z-10">
                <h3 class="font-bold text-lg text-gray-700">Conversations</h3>
                <p class="text-xs text-gray-400 mt-1">Select a user to start chatting</p>
            </div>
            
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                @forelse($users as $user)
                    <a href="{{ route('admin.chat.admin.detail', $user->id) }}" 
                       class="flex items-center gap-3 p-4 border-b border-gray-100 hover:bg-white transition-all cursor-pointer group {{ isset($activeUser) && $activeUser->id == $user->id ? 'bg-purple-50 border-l-4 border-purple-600' : 'hover:border-l-4 hover:border-gray-300' }}">
                        
                        <div class="relative">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold text-lg shadow-md shrink-0">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-white rounded-full"></div>
                        </div>
                        
                        <div class="flex-1 min-w-0 text-left">
                            <div class="flex justify-between items-baseline mb-1">
                                <h4 class="font-semibold text-gray-800 truncate group-hover:text-purple-600 transition-colors text-sm">{{ $user->name }}</h4>
                                <span class="text-xs text-gray-400">{{ $user->chats->first() ? $user->chats->first()->created_at->format('H:i') : '' }}</span>
                            </div>
                            <p class="text-sm text-gray-500 truncate">
                                {{ $user->chats->first() ? $user->chats->first()->message : 'No messages' }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-gray-400 p-6">
                        <i class="fa-solid fa-comments text-4xl mb-3 text-gray-300"></i>
                        <p class="text-center text-sm">No conversations yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- MAIN: CHAT WINDOW -->
        <div class="w-2/3 flex flex-col bg-[#efeae2] relative" 
             style="background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png'); background-blend-mode: overlay;">
            
            @if(isset($activeUser))
                <!-- Chat Header -->
                <div class="p-4 bg-white border-b border-gray-200 shadow-sm flex items-center justify-between z-10 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold shadow-sm">
                            {{ substr($activeUser->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm md:text-base">{{ $activeUser->name }}</h3>
                            <p class="text-xs text-green-600 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Online
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">
                    @forelse($chats as $chat)
                        @if($chat->sender_type === 'admin')
                            <!-- Admin Message (Right) -->
                            <div class="flex justify-end group">
                                <div class="bg-purple-600 text-white rounded-l-xl rounded-tr-xl rounded-br-none px-4 py-2 shadow-sm max-w-[70%] relative">
                                    <p class="text-sm">{{ $chat->message }}</p>
                                    <span class="text-[10px] text-purple-200 mt-1 block text-right">
                                        {{ $chat->created_at->format('H:i') }}
                                        <i class="fa-solid fa-check-double ml-1"></i>
                                    </span>
                                </div>
                            </div>
                        @else
                            <!-- User Message (Left) -->
                            <div class="flex justify-start group">
                                <div class="bg-white text-gray-800 rounded-r-xl rounded-tl-xl rounded-bl-none px-4 py-2 shadow-sm max-w-[70%] border border-gray-100 relative">
                                    <p class="text-sm">{{ $chat->message }}</p>
                                    <span class="text-[10px] text-gray-400 mt-1 block">
                                        {{ $chat->created_at->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    @empty
                         <div class="text-center text-gray-400 mt-10">
                            <p>No messages yet.</p>
                         </div>
                    @endforelse
                </div>

                <!-- Input Area -->
                <div class="p-4 bg-gray-50 border-t border-gray-200 shrink-0">
                    <form action="{{ route('admin.chat.store') }}" method="POST" class="flex gap-3 items-center">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $activeUser->id }}">
                    
                        <input type="text" name="message" 
                               class="flex-1 rounded-full border border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 transition-all px-4 py-3 text-sm outline-none" 
                               placeholder="Type a message..." required autofocus autocomplete="off">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white rounded-full p-3 w-12 h-12 flex items-center justify-center shadow-lg transition-transform active:scale-95">
                            <i class="fa-solid fa-paper-plane text-lg"></i>
                        </button>
                    </form>
                </div>
            @else
                <!-- Empty State -->
                <div class="flex-1 flex flex-col items-center justify-center text-gray-400 select-none">
                    <div class="bg-gray-100 p-6 rounded-full mb-4 shadow-inner">
                        <i class="fa-solid fa-comments text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-500">Welcome to Admin Chat</h3>
                    <p class="text-sm mt-2">Select a conversation from the left sidebar to start messaging.</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent; 
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
    </style>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto scroll to bottom
        const messageContainer = document.getElementById('chat-messages');
        if (messageContainer) {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }
    });
</script>
@endsection