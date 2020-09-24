<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Traits\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['username', 'password']);
        $token = Auth::attempt($credentials);
        if (!$token) {
            return Helpers::apiResponse(false, 'Username or Password Is Wrong', [], 401);
        }

        $user = Auth::user();
        $data['token'] = $token;
        $newAvatar = Storage::url($user->profile->avatar);
        $data['name'] = $user->name;
        $data['avatar'] = $newAvatar;

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
