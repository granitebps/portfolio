<?php

namespace App\Http\Middleware;

use App\Traits\Helpers;
use App\User;
use Closure;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;

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
        if ($decoded->iss !== 'granitebps.com') {
            return Helpers::apiResponse(false, 'Unauthorized', [], 401);
        }
        $user = User::where('email', $decoded->sub)->first();
        if (empty($user)) {
            return Helpers::apiResponse(false, 'Unauthorized', [], 401);
        }
        if ($decoded->iss !== 'granitebps.com') {
            return Helpers::apiResponse(false, 'Unauthorized', [], 401);
        }
        $user_token = base64_decode($user->token);
        if ($user_token !== $token) {
            return Helpers::apiResponse(false, 'Unauthorized', [], 401);
        }
        $request->payload = $decoded;
        return $next($request);
    }
}
