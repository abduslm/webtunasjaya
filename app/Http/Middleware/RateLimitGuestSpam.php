<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitGuestSpam
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $action = 'default'): Response
    {
        // 1. Ambil IP Address Guest untuk dijadikan identitas unik kunci limiter
        $key = $action . ':' . $request->ip();

        // 2. Set Batasan: Maksimal 3 kali request per 1 menit (60 detik)
        if (RateLimiter::tooManyAttempts($key, $maxAttempts = 3)) {
            $seconds = RateLimiter::availableIn($key);
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => "Terlalu banyak permintaan. Silakan coba lagi dalam $seconds detik."
                ], 429);
            }

            return back()->withErrors([
                'spam' => "Anda terlalu cepat melakukan aksi ini. Silakan tunggu $seconds detik."
            ]);
        }

        RateLimiter::hit($key, $decaySeconds = 60);
        return $next($request);
    }
}
