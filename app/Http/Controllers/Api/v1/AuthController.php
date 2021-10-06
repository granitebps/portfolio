<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\ResetPassword;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Traits\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['username', 'password']);

        $user = User::where('username', $credentials['username'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return Helpers::apiResponse(false, 'Username or Password Is Wrong', [], 401);
        }

        $token = $user->createToken(config('app.name'));

        if (!$token) {
            return Helpers::apiResponse(false, 'Unauthorized', [], 401);
        }

        $user = $user;
        $data['token'] = $token->plainTextToken;
        $data['name'] = $user->name;
        $data['avatar'] = $user->profile->avatar;

        return Helpers::apiResponse(true, '', $data);
    }

    public function me()
    {
        $user = Auth::user();
        if (!$user) {
            return Helpers::apiResponse(false, 'Unauthorized', [], 401);
        }
        return Helpers::apiResponse(true, '', $user);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return Helpers::apiResponse(false, 'Unauthorized', [], 401);
        }
        $request->user()->currentAccessToken()->delete();
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
        if ($validator->fails()) {
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

        if (!$reset->is_valid) {
            return view('reset_password')->with([
                'is_valid' => false,
            ]);
        }
        if (Carbon::now()->gt($reset->expired_at)) {
            return view('reset_password')->with([
                'is_valid' => false,
            ]);
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
