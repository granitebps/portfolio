<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Portfolio extends Model
{
    use SoftDeletes;

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
        return $this->hasMany(PortfolioPic::class, 'portfolio_id', 'id');
    }
}
