<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Traits\Helpers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'phone' => 'required|numeric',
            'address' => 'required',
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

                // Hosting
                $avatar->storeAs('avatar', $nama_avatar, 'hosting');

                // Storage::putFileAs('public/images/avatar', $avatar, $avatarName);
                $user->profile->update([
                    'avatar' => $nama_avatar,
                ]);
            }
            if ($request->hasFile('cv')) {
                $cv = $request->cv;
                $cvName = 'cv.pdf';

                // Hosting
                $cv->storeAs('cv', $cvName, 'hosting');

                // Storage::putFileAs('public/images/cv', $cv, $cvName);
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
                'phone' => $request->phone,
                'address' => $request->address,
                'instagram' => $request->instagram,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'youtube' => $request->youtube,
                'github' => $request->github,
                'linkedin' => $request->linkedin,
            ]);
            DB::commit();

            return Helpers::apiResponse(true, 'Profile Updated');
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
