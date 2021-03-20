<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    protected $casts = [
        'languages' => 'array',
    ];
}
