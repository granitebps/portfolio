<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Services extends Model
{
    protected $table = 'services';
    protected $guarded = ['created_at', 'updated_at'];

    protected static function booted()
    {
        static::saved(function ($service) {
            Cache::forget('services');
        });
        static::deleted(function ($service) {
            Cache::forget('services');
        });
    }
}
