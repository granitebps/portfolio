<?php

namespace Tests\Traits;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

trait AuthTraitTest
{
    public function authenticate()
    {
        $user = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make(12345678),
            'token' => 'abcde'
        ]);

        Profile::create([
            'user_id' => $user->id,
            'avatar' => '/'
        ]);

        // return $token;
    }
}
