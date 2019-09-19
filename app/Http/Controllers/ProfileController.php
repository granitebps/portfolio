<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $data['title'] = 'Edit Profile';
        $data['user'] = User::first();
        return view('admin.profile.edit')->with($data);
    }

    public function update(Request $request)
    {
        $user = User::first();
        $profile = Profile::where('user_id', $user->id)->first();
        $this->validate($request, [
            'username' => 'required|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'name' => 'required|max:255',
            'avatar' => 'max:1024|image',
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
            if ($request->hasFile('avatar')) {
                $avatar = $request->avatar;
                $avatarName = $avatar->getClientOriginalName();

                // Hosting
                $avatar->storeAs('avatar', $avatarName, 'hosting');

                // Storage::putFileAs('public/images/avatar', $avatar, $avatarName);
                $profile->update([
                    'avatar' => $avatarName,
                ]);
            }
            if ($request->hasFile('cv')) {
                $cv = $request->cv;
                $cvName = 'cv.pdf';

                // Hosting
                $cv->storeAs('cv', $cvName, 'hosting');

                // Storage::putFileAs('public/images/cv', $cv, $cvName);
                $profile->update([
                    'cv' => $cvName,
                ]);
            }
            $user->update([
                'username' => $request->username,
                'email' => $request->email,
                'name' => $request->name,
            ]);
            $profile->update([
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

            Session::flash('success', 'Profile Edited');
            return redirect()->route('profile.edit');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something Wrong');
            return redirect()->back();
        }
    }

    public function password()
    {
        $data['title'] = 'Change Password';
        $data['user'] = User::first();
        return view('admin.profile.password')->with($data);
    }

    public function changePassword(Request $request)
    {
        $user = User::first();
        if (!Hash::check($request->old_password, $user->password)) {
            Session::flash('error', 'Old Password Does Not Match');
            return redirect()->back();
        } else {
            $this->validate($request, [
                'password' => 'required|confirmed|min:8|string'
            ]);
            DB::beginTransaction();
            try {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);

                DB::commit();
                Session::flash('success', 'Password Changed');
                return redirect()->route('profile.password');
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('error', 'Something Wrong');
                return redirect()->back();
            }
        }
    }
}
