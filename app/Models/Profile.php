<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use ClearsResponseCache;

    protected $fillable = [
        'user_id',
        'about',
        'avatar',
        'phone',
        'address',
        'instagram',
        'facebook',
        'twitter',
        'linkedin',
        'github',
        'youtube',
        'cv',
        'nationality',
        'languages',
        'freelance',
        'medium',
        'birth'
    ];

    protected $casts = [
        'languages' => 'array',
        'freelance' => 'boolean'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getAvatarAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }

        return null;
    }

    public function getCvAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }

        return null;
    }
}
