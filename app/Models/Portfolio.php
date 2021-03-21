<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portfolio extends Model
{
    use SoftDeletes, ClearsResponseCache;

    protected $table = 'portfolios';
    protected $guarded = ['created_at', 'updated_at'];

    public function pic()
    {
        return $this->hasMany(PortfolioPic::class, 'portfolio_id', 'id');
    }
}
