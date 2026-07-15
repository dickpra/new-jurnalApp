<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetDynamicTimezone
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ambil zona waktu dari Cookie JavaScript
        $tz = $request->cookie('client_timezone');

        // 2. Validasi biar gak di-hack / error kalau cookie kosong
        if ($tz && in_array($tz, \DateTimeZone::listIdentifiers())) {
            // Ubah zona waktu khusus untuk request (klik) yang sedang berjalan ini
            date_default_timezone_set($tz);
            config(['app.timezone' => $tz]);
        }

        return $next($request);
    }
}