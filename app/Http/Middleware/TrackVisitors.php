<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class TrackVisitors
{
    public function handle(Request $request, Closure $next)
    {
        // Logika: Catat IP jika belum ada di database hari ini (Unique Visitor per day)
        $ip = $request->ip();
        $today = now()->format('Y-m-d');

        $exists = Visitor::where('ip_address', $ip)
                         ->whereDate('created_at', $today)
                         ->exists();

        if (!$exists) {
            $location = Location::get($ip); // Memakai paket stevebauman/location yang kita bahas tadi

            Visitor::create([
                'ip_address' => $ip,
                'country' => $location ? $location->countryName : 'Unknown',
                'city' => $location ? $location->cityName : 'Unknown',
            ]);
        }

        return $next($request);
    }
}