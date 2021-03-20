<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    protected $table = 'reset_passwords';
    protected $guarded = ['created_at', 'updated_at'];

    protected $casts = [
        'is_valid' => 'boolean',
    ];
}
