<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreLastVisitedUrl
{
    public function handle(Request $request, Closure $next)
    {
        // Jika sudah login sebagai admin → JANGAN simpan URL
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Simpan URL terakhir untuk user biasa saja
        if (
            $request->method() === 'GET' &&
            ! $request->expectsJson() &&
            ! $request->is('login') &&
            ! $request->is('register') &&
            ! $request->is('admin/*') // jangan simpan halaman admin
        ) {
            session(['url.intended' => $request->fullUrl()]);
        }

        return $next($request);
    }
}
