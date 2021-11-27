<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Propaganistas\LaravelPhone\PhoneNumber;

class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        $user = User::with('profile')->first();
        return Helpers::apiResponse(true, '', $user);
    }

    public function update(Request $request): JsonResponse
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
            'birth' => 'required|string|max:255',
            'phone' => 'required|phone:ID,ID,mobile',
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
                $nama_avatar = 'avatar.jpg';

                $jpg = Helpers::compressImageIntervention($avatar);

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
            $phone = (string) PhoneNumber::make($request->phone, 'ID');
            $user->profile->update([
                'about' => $request->about,
                'birth' => $request->birth,
                'phone' => $phone,
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

            return Helpers::apiResponse(true, 'Profile Updated', [
                'name' => $user->name,
                'avatar' => $user->profile->avatar,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function password(Request $request): JsonResponse
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
