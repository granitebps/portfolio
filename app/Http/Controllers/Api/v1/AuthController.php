<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Traits\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['username', 'password']);
        if ($request->remember_me === true) {
            $this->jwt->factory()->setTTL(518400);
        }
        $token = $this->jwt->attempt($credentials, $request->remember_me);

        if (!$token) {
            return Helpers::apiResponse(false, 'Username or Password Is Wrong', [], 401);
        }

        $user = Auth::user();
        $data['token'] = $token;
        $newAvatar = Storage::url($user->profile->avatar);
        $data['name'] = $user->name;
        $data['avatar'] = $newAvatar;
        $data['expires_in'] = auth()->factory()->getTTL() * 60;

        return Helpers::apiResponse(true, '', $data);
    }

    public function me()
    {
        $user = Auth::user();
        if (!$user) {
            return Helpers::apiResponse(false, 'Unauthorized', [], 401);
        }
        $newAvatar = Storage::url($user->profile->avatar);
        $user->profile->avatar = $newAvatar;
        return Helpers::apiResponse(true, '', $user);
    }

    public function logout()
    {
        $user = Auth::user();
        if (!$user) {
            return Helpers::apiResponse(false, 'Unauthorized', [], 401);
        }
        Auth::logout();
        return Helpers::apiResponse(true, 'User Logged Out');
    }
}
