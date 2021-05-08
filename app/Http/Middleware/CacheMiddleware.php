<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('GET')) {
            $url = $request->getRequestUri();
            $prefix = auth()->check() ? auth()->id() : '';
            $key = $prefix . ":" . $url;
            $key_timestamp = $prefix . ":" . $url . ":timestamp";
            if (Cache::has($key)) {
                $timestamp = Cache::get($key_timestamp);
                $response = Cache::get($key);
                if ($response) {
                    return $response->header('cache-timestamp', $timestamp);
                }
            }
        }

        $response = $next($request);

        if ($request->isMethod('GET')) {
            $url = $request->getRequestUri();

            $prefix = auth()->check() ? auth()->id() : '';

            $key = $prefix . ":" . $url;
            $key_timestamp = $prefix . ":" . $url . ":timestamp";

            Cache::put($key, $response);
            Cache::put($key_timestamp, now()->timestamp);
        }

        return $response;
    }
}
