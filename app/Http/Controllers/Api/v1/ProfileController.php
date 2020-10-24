<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Traits\Helpers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        if (Cache::has('profile')) {
            $user = Cache::get('profile');
        } else {
            $user = User::with('profile')->first();
            $user->makeHidden(['created_at', 'updated_at']);
            $user->profile->makeHidden(['created_at', 'updated_at', 'id', 'user_id']);
            $newAvatar = Storage::url($user->profile->avatar);
            $user->profile->avatar = $newAvatar;
            $newCv = Storage::url($user->profile->cv);
            $user->profile->cv = $newCv;
            $user->profile->freelance = (int)$user->profile->freelance;
            Cache::put('profile', $user, now()->addDay());
        }
        return Helpers::apiResponse(true, '', $user);
    }

    public function update(Request $request)
    {
        $auth = auth()->user();
        if (!$auth) {
            return Helpers::apiResponse(false, 'Unauthenticated', [], 401);
        }
        $user = User::with('profile')->find($auth->id);
        $this->validate($request, [
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'name' => 'required|string|max:255',
            'avatar' => 'sometimes|nullable|max:2048|image',
            'about' => 'required|string',
            'age' => 'required|numeric',
            'phone' => 'required|numeric',
            'address' => 'required|string',
            'nationality' => 'required|string|max:255',
            'languages' => 'required|array',
            'freelance' => 'required|boolean',
            'instagram' => 'required|url|string|max:255',
            'facebook' => 'required|url|string|max:255',
            'twitter' => 'required|url|string|max:255',
            'youtube' => 'required|url|string|max:255',
            'github' => 'required|url|string|max:255',
            'linkedin' => 'required|url|string|max:255',
            'medium' => 'required|url|string|max:255',
            'cv' => 'mimes:pdf|file|max:2048'
        ]);
        DB::beginTransaction();
        try {
            if (!$user) {
                return Helpers::apiResponse(false, 'User Not Found', [], 404);
            }

            if ($request->hasFile('avatar')) {
                $avatar = $request->avatar;
                $nama_avatar = time() . '_' . md5(uniqid()) . '.jpg';

                $jpg = Helpers::compressImageCloudinary($avatar);

                Storage::deleteDirectory('avatar');

                $aws_avatar = 'avatar/' . $nama_avatar;
                Storage::put($aws_avatar, $jpg);

                $user->profile->update([
                    'avatar' => $aws_avatar,
                ]);
            }
            if ($request->hasFile('cv')) {
                $cv = $request->cv;
                $cvName = time() . '_' . 'cv.pdf';

                Storage::deleteDirectory('cv');
                $aws_cv = Storage::putFileAs('cv', $cv, $cvName);
                $user->profile->update([
                    'cv' => $aws_cv,
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
                'medium' => $request->medium,
            ]);
            DB::commit();

            Cache::forget('profile');

            return Helpers::apiResponse(true, 'Profile Updated', [
                'token' => Auth::refresh(),
                'name' => $user->name,
                'avatar' => Storage::url($user->profile->avatar),
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function password(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|string|min:8|max:255',
            'old_password' => 'required|string|min:8|max:255'
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
                throw $e;
            }
        }
    }
}
