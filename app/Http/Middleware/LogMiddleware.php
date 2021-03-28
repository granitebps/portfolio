<?php

namespace App\Http\Middleware;

use App\StorableEvents\LogApiEvent;
use Closure;
use Illuminate\Http\Request;

class LogMiddleware
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
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function terminate($request, $response)
    {
        event(new LogApiEvent(
            now(),
            $request->fullUrl(),
            $request->method(),
            json_decode($request->getContent(), true),
            $request->header(),
            $request->ip(),
            $response->getStatusCode()
        ));
    }
}
