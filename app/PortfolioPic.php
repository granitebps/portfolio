<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PortfolioPic extends Model
{
    protected $guarded = ['created_at', 'updated_at'];
    protected $table = 'portfolios_pic';
}
