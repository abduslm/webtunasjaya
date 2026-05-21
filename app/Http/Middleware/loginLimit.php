<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class loginLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $limit = $user->role === 'spv' ? 3 : 5;
            $cacheKey = 'login_attempts_' . $user->id;
            $attempts = Cache::get($cacheKey, 0);

            if ($attempts >= $limit-1) {
                return redirect('/login')->withErrors([
                    'error' => "Login diblokir sementara untuk user {$user->email} setelah {$limit} percobaan gagal. Coba lagi dalam 5 menit.",
                ]);
            }
        }

        return $next($request);
    }
}
