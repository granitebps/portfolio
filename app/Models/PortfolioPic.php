<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortfolioPic extends Model
{
    use SoftDeletes, ClearsResponseCache;

    protected $guarded = ['created_at', 'updated_at'];
    protected $table = 'portfolios_pic';
}
