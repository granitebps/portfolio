<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $guarded = ['created_at', 'updated_at'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saved(function ($blog) {
            Cache::forget('blogs');
        });
        static::deleted(function ($blog) {
            Cache::forget('blogs');
        });
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
