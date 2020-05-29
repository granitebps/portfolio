<?php

namespace App\Http\Middleware;

use App\Traits\Helpers;
use App\User;
use Carbon\Carbon;
use Closure;
use \Firebase\JWT\JWT;

class AuthToken
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
        $token = $request->bearerToken();
        if (empty($token)) {
            return Helpers::apiResponse(false, 'Unauthorized', [], 401);
        }
        $secret = config('jwt.secret');
        try {
            $decoded = JWT::decode($token, $secret, array('HS256'));
        } catch (\Exception $e) {
            return Helpers::apiResponse(false, 'Unauthorized', [], 401);
        }
        $user = User::where('email', $decoded->sub)->first();
        if (empty($user)) {
            return Helpers::apiResponse(false, 'Unauthorized', [], 401);
        }
        $request->payload = $decoded;
        return $next($request);
    }
}
