<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Education extends Model
{
    protected $guarded = ['created_at', 'updated_at'];
    protected $table = 'educations';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saved(function ($education) {
            Cache::forget('educations');
        });
        static::deleted(function ($education) {
            Cache::forget('educations');
        });
    }
}
