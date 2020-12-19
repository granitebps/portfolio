<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Skill extends Model
{
    protected $table = 'skills';
    protected $guarded = ['created_at', 'updated_at'];

    protected static function booted()
    {
        static::saved(function ($skill) {
            Cache::forget('skills');
        });
        static::deleted(function ($skill) {
            Cache::forget('skills');
        });
    }
}
