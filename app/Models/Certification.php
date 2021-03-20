<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Certification extends Model
{
    use SoftDeletes;

    protected $table = 'certifications';
    protected $guarded = ['created_at', 'updated_at'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saved(function ($certification) {
            Cache::forget('certifications');
        });
        static::deleted(function ($certification) {
            Cache::forget('certifications');
        });
    }
}
