<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ClearsResponseCache
{
    public static function bootClearsResponseCache()
    {
        self::saved(function () {
            Cache::flush();
        });

        self::created(function () {
            Cache::flush();
        });

        self::updated(function () {
            Cache::flush();
        });

        self::deleted(function () {
            Cache::flush();
        });
    }
}
