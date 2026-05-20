<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiCors
{
    public function handle(Request $request, Closure $next): Response
    {
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept, Origin',
        ];

        if ($request->is('api/*') && $request->isMethod('OPTIONS')) {
            return response()->noContent()->withHeaders($headers);
        }

        $response = $next($request);

        if ($request->is('api/*')) {
            $response->headers->add($headers);
        }

        return $response;
    }
}
