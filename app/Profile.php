<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
