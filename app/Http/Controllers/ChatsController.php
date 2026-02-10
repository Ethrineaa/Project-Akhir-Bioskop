<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chats;

class ChatsController extends Controller
{
    // ADMIN: Halaman utama chat (list user)
    public function adminChat()
    {
        // Ambil user yang pernah chat, urutkan berdasarkan chat terbaru
        $users = \App\Models\User::whereHas('chats')
            ->with([
                'chats' => function ($query) {
                    $query->latest();
                }
            ])
            ->get()
            ->sortByDesc(function ($user) {
                return $user->chats->first()->created_at;
            });

        return view('admin.chat.index', compact('users'));
    }

    // ADMIN: Detail chat dengan spesifik user
    public function adminChatDetail($userId)
    {
        $users = \App\Models\User::whereHas('chats')
            ->with([
                'chats' => function ($query) {
                    $query->latest();
                }
            ])
            ->get()
            ->sortByDesc(function ($user) {
                return $user->chats->first()->created_at;
            });

        $activeUser = \App\Models\User::findOrFail($userId);

        // Ambil chat specific user
        $chats = Chats::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.chat.index', compact('users', 'activeUser', 'chats'));
    }

    // SHARED: Simpan pesan (User & Admin)
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id', // Pastikan ada user_id target/sender
        ]);

        // Tukan sender_type berdasarkan role login
        $senderType = auth()->user()->role === 'admin' ? 'admin' : 'user';

        Chats::create([
            'user_id' => $request->user_id, // Kalau admin, ini ID user yang dibalas. Kalau user, ini ID dia sendiri.
            'sender_type' => $senderType,
            'message' => $request->message,
        ]);

        return back();
    }
}
