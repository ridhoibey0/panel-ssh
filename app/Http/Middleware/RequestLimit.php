<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RequestLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->header('CF-Connecting-IP');
        $key = 'request_limit:' . $ip;
        $limit = 2; // Jumlah maksimal request per hari
    
        $requests = Cache::get($key, 0);
        if ($requests >= $limit) {
            return response()->json(['status' => 'error', 'message' => 'Batasan request harian telah tercapai']);
        }
    
        Cache::increment($key, 1, now()->addDay()->startOfDay());
    
        return $next($request);
    }
}
