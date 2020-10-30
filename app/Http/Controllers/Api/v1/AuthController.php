<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Notifications\ResetPasswordNotification;
use App\ResetPassword;
use App\Traits\Helpers;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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

    public function request_reset_password(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|exists:users,email'
        ]);

        DB::beginTransaction();
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return Helpers::apiResponse(false, 'User Not Found', [], 400);
            }

            $token = bin2hex(random_bytes(50));
            ResetPassword::create([
                'user_id' => $user->id,
                'token' => $token,
                'is_valid' => true,
                'expired_at' => now()->addHour()
            ]);

            $user->notify(new ResetPasswordNotification($user, $token));

            DB::commit();

            return Helpers::apiResponse(true, 'Send Reset Password Request Email');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function reset_password_form($token)
    {
        $is_valid = false;

        $reset = ResetPassword::where('token', $token)->first();
        if ($reset) {
            if ($reset->is_valid === true) {
                if (!Carbon::now()->gt($reset->expired_at)) {
                    $is_valid = true;
                }
            }
        }

        return view('reset_password')->with([
            'is_valid' => $is_valid,
            'token' => $token,
            'reset' => $reset
        ]);
    }

    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|confirmed|string|min:8|max:255',
        ]);
        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        $reset = ResetPassword::where('token', $request->token)->first();
        if (!$reset) {
            return back()->withErrors(['error' => 'Token Invalid!']);
        }

        $user = User::where('id', $reset->user_id)->first();
        if (!$user) {
            return back()->withErrors(['error' => 'Token Invalid!']);
        }

        DB::beginTransaction();
        try {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            $reset->update([
                'is_valid' => false
            ]);

            DB::commit();
            return view('reset_password')->with([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Something Wrong!']);
        }
    }
}
