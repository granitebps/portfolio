<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Portfolio extends Model
{
    protected $table = 'portfolios';
    protected $guarded = ['created_at', 'updated_at'];

    protected static function booted()
    {
        static::saved(function ($portfolio) {
            Cache::forget('portfolio');
        });
        static::deleted(function ($portfolio) {
            Cache::forget('portfolio');
        });
    }

    public function pic()
    {
        return $this->hasMany('App\PortfolioPic', 'portfolio_id', 'id');
    }
}
