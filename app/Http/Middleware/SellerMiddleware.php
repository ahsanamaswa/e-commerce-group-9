<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Store;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // User harus login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Cek apakah user sudah punya toko
        $store = Store::where('user_id', auth()->id())->first();

        // Jika belum punya toko dan bukan di halaman register/pending
        if (!$store && !$request->routeIs('store.register') && !$request->routeIs('store.register.submit')) {
            return redirect()->route('store.register')
                ->with('info', 'Silakan daftarkan toko Anda terlebih dahulu.');
        }

        // Jika sudah punya toko tapi belum verified dan bukan di halaman pending
        if ($store && !$store->is_verified && !$request->routeIs('store.pending')) {
            return redirect()->route('store.pending');
        }

        return $next($request);
    }
}