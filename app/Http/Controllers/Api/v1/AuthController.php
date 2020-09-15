<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Traits\Helpers;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function get_token()
    {
        $secret = config('jwt.secret');
        $payload = [
            'sub' => 'admin',
            'iat' => Carbon::now()->timestamp,
            'exp' => Carbon::now()->addHours(24)->timestamp,
        ];
        $jwt = JWT::encode($payload, $secret);
        return Helpers::apiResponse(true, '', $jwt);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string|min:8'
        ]);

        $credentials = request(['username', 'password']);
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
}
