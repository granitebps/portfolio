<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortfolioPic extends Model
{
    use SoftDeletes;

    protected $guarded = ['created_at', 'updated_at'];
    protected $table = 'portfolios_pic';
}
