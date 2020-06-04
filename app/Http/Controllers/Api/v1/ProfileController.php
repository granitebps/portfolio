<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Traits\Helpers;
use App\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::with('profile')->first();
        $user->makeHidden(['created_at', 'updated_at']);
        $user->profile->makeHidden(['created_at', 'updated_at', 'id', 'user_id']);
        $newAvatar = asset('images/avatar/' . $user->profile->avatar);
        $user->profile->avatar = $newAvatar;
        $newCv = asset('images/cv/' . $user->profile->cv);
        $user->profile->cv = $newCv;
        return Helpers::apiResponse(true, '', $user);
    }

    public function update(Request $request)
    {
        $user = User::with('profile')->first();
        $this->validate($request, [
            'username' => 'required|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'name' => 'required|max:255',
            'avatar' => 'max:2048|image',
            'about' => 'required',
            'age' => 'required|numeric',
            'phone' => 'required|numeric',
            'address' => 'required',
            'nationality' => 'required',
            'languages' => 'required',
            'freelance' => 'required',
            'instagram' => 'required|url',
            'facebook' => 'required|url',
            'twitter' => 'required|url',
            'youtube' => 'required|url',
            'github' => 'required|url',
            'linkedin' => 'required|url',
            'cv' => 'mimes:pdf|file|max:2048'
        ]);
        DB::beginTransaction();
        try {
            if (!$user) {
                return Helpers::apiResponse(false, 'User Not Found', [], 404);
            }

            if ($request->hasFile('avatar')) {
                $avatar = $request->avatar;
                $avatar_full = $avatar->getClientOriginalName();
                $filename = Str::slug(pathinfo($avatar_full, PATHINFO_FILENAME));
                $extension = pathinfo($avatar_full, PATHINFO_EXTENSION);
                $nama_avatar = time() . '_' . $filename . '.' . $extension;

                Storage::deleteDirectory('avatar');
                Storage::putFileAs('avatar', $avatar, $nama_avatar);
                $user->profile->update([
                    'avatar' => $nama_avatar,
                ]);
            }
            if ($request->hasFile('cv')) {
                $cv = $request->cv;
                $cvName = 'cv.pdf';

                Storage::putFileAs('cv', $cv, $cvName);
                $user->profile->update([
                    'cv' => $cvName,
                ]);
            }
            $user->update([
                'username' => $request->username,
                'email' => $request->email,
                'name' => $request->name,
            ]);
            $user->profile->update([
                'about' => $request->about,
                'age' => $request->age,
                'phone' => $request->phone,
                'address' => $request->address,
                'nationality' => $request->nationality,
                'languages' => $request->languages,
                'freelance' => $request->freelance,
                'instagram' => $request->instagram,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'youtube' => $request->youtube,
                'github' => $request->github,
                'linkedin' => $request->linkedin,
            ]);
            DB::commit();

            $secret = config('jwt.secret');
            $payload = [
                'sub' => $user->email,
                'iat' => Carbon::now()->timestamp,
                'exp' => Carbon::now()->addHours(24)->timestamp,
            ];
            $jwt = JWT::encode($payload, $secret);

            $newAvatar = asset('images/avatar/' . $user->profile->avatar);
            return Helpers::apiResponse(true, 'Profile Updated', ['token' => $jwt, 'name' => $user->name, 'avatar' => $newAvatar]);
        } catch (\Exception $e) {
            DB::rollback();
            return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
        }
    }

    public function password(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:8|string',
            'old_password' => 'required|min:8|string'
        ]);
        $user = User::first();
        if (!Hash::check($request->old_password, $user->password)) {
            return Helpers::apiResponse(false, 'Old Password Does Not Match', [], 400);
        } else {
            DB::beginTransaction();
            try {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);

                DB::commit();
                return Helpers::apiResponse(true, 'Password Changed');
            } catch (\Exception $e) {
                DB::rollback();
                return Helpers::apiResponse(false, 'Something Wrong!', $e->getMessage(), 500);
            }
        }
    }
}
