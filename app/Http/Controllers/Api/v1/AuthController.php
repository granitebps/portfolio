<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Traits\Helpers;
use App\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return Helpers::apiResponse(false, 'Username or Password Is Wrong', [], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return Helpers::apiResponse(false, 'Username or Password Is Wrong', [], 401);
        }

        $secret = config('jwt.secret');
        $payload = [
            'iss' => 'granitebps.com',
            'sub' => $user->email,
            'iat' => Carbon::now()->timestamp,
            'exp' => Carbon::now()->addHours(24)->timestamp,
        ];
        $jwt = JWT::encode($payload, $secret);
        $data['token'] = $jwt;
        $newAvatar = Storage::url($user->profile->avatar);
        $data['name'] = $user->name;
        $data['avatar'] = $newAvatar;

        $user->token = base64_encode($jwt);
        $user->save();

        return Helpers::apiResponse(true, '', $data);
    }

    public function me(Request $request)
    {
        $email = $request->payload->sub;
        $user = User::with('profile')->where('email', $email)->first();
        if (!$user) {
            return Helpers::apiResponse(false, 'Email or Password Is Wrong', [], 401);
        }
        $newAvatar = Storage::url($user->profile->avatar);
        $user->profile->avatar = $newAvatar;
        return Helpers::apiResponse(true, '', $user);
    }
}
