<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Experience extends Model
{
    protected $table = 'experiences';
    protected $guarded = ['created_at', 'updated_at'];

    protected static function booted()
    {
        static::saved(function ($experience) {
            Cache::forget('experiences');
        });
        static::deleted(function ($experience) {
            Cache::forget('experiences');
        });
    }
}
