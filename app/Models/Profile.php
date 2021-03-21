<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use ClearsResponseCache;

    protected $guarded = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    protected $casts = [
        'languages' => 'array',
    ];
}
