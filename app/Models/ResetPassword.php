<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Database\Factories\ResetPasswordFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    use ClearsResponseCache, HasFactory;

    protected $table = 'reset_passwords';
    protected $fillable = [
        'user_id',
        'token',
        'is_valid',
        'expired_at'
    ];

    protected $casts = [
        'is_valid' => 'boolean',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ResetPasswordFactory::new();
    }
}
