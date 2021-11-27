<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Database\Factories\ProfileFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use ClearsResponseCache, HasFactory;

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

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getAvatarAttribute(string|null $value): string
    {
        if ($value) {
            return Storage::url($value);
        }

        return '';
    }

    public function getCvAttribute(string|null $value): string
    {
        if ($value) {
            return Storage::url($value);
        }

        return '';
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ProfileFactory::new();
    }
}
