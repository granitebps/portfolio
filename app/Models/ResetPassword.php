<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    use ClearsResponseCache;

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
}
