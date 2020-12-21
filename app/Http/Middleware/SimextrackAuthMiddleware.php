<?php

namespace App\Http\Middleware;

use App\Models\Simextrack\User;
use Closure;
use Illuminate\Auth\AuthenticationException;

class SimextrackAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uuid = $request->bearerToken();
        $user = User::where('uuid', $uuid)->first();
        if (!$user) {
            throw new AuthenticationException();
        }
        $request->merge(['user' => $user]);
        return $next($request);
    }
}
