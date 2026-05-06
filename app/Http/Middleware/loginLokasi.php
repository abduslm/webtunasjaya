<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class loginLokasi
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::User();

        $userLokasi = null;
        if ($user->dataKaryawan && $user->dataKaryawan->lokasi) {
            $userLokasi = $user->dataKaryawan->lokasi->alamat;
        }

        $inputLokasi = $request->lokasi;

        if ($userLokasi !== null && $inputLokasi != $userLokasi) {
            return abort(403, 'Lokasi yang dipilih tidak sesuai dengan lokasi milik user.');
        }
        return $next($request);
    }
}
