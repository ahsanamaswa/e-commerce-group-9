<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $store = auth()->user()->store;

        if (!$store) {
            return redirect()->route('store.register')
                ->with('error', 'You need to register a store first.');
        }

        if (!$store->is_verified) {
            return redirect()->route('store.pending')
                ->with('warning', 'Your store is pending verification.');
        }

        return $next($request);
    }
}