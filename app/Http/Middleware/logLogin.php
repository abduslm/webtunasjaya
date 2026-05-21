<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class logLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('Incoming Request', [
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        $response = $next($request);
        if (Auth::check()) {
            Log::info('Login attempt', [
                'status' => 'login successful',
                'email' => Auth::user()->email,
                'role' => Auth::user()->role,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }else{
            Log::info('Login attempt', [
                'status' => 'login failed',
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}
