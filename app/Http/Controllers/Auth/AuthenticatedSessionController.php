<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login form.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function store(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Attempt login
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // 🔥 ADMIN SELALU MASUK DASHBOARD TANPA INTENDED
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        // 🔥 USER BIASA → intended (hal terakhir)
        return redirect()->intended('/');
    }

    /**
     * Logout user.
     */
    public function destroy(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
