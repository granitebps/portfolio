<?php

namespace App\Http\Controllers\Api\v1\Simextrack;

use App\Http\Controllers\Controller;
use App\Traits\Helpers;
use App\Models\Simextrack\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username',
                'password' => 'required|string|min:8|max:255|confirmed'
            ]);
            $input = $request->all();

            $input['uuid'] = Str::uuid();
            $input['password'] = Hash::make($input['password']);

            $user = User::create($input);
            $user->makeHidden(['password']);

            DB::commit();

            return Helpers::apiResponse(true, '', $user);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:255'
        ]);

        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return Helpers::apiResponse(false, 'Wrong username/password!', [], 400);
        }

        if (!Hash::check($request->password, $user->password)) {
            return Helpers::apiResponse(false, 'Wrong username/password!', [], 400);
        }

        $user->makeHidden(['password']);

        return Helpers::apiResponse(true, '', $user);
    }

    public function me(Request $request)
    {
        return Helpers::apiResponse(true, '', [
            'user' => $request->user
        ]);
    }
}
