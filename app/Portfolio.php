<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $guarded = ['created_at', 'updated_at'];

    public function pic()
    {
        return $this->hasMany('App\PortfolioPic', 'portfolio_id', 'id');
    }
}
