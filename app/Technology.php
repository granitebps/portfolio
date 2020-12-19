<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Technology extends Model
{
    protected $table = 'technologies';
    protected $guarded = ['created_at', 'updated_at'];

    protected static function booted()
    {
        static::saved(function ($tech) {
            Cache::forget('tech');
        });
        static::deleted(function ($tech) {
            Cache::forget('tech');
        });
    }
}
