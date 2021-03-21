<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    use ClearsResponseCache;

    protected $table = 'reset_passwords';
    protected $guarded = ['created_at', 'updated_at'];

    protected $casts = [
        'is_valid' => 'boolean',
    ];
}
