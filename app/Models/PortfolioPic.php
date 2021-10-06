<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class PortfolioPic extends Model
{
    use SoftDeletes, ClearsResponseCache;

    protected $table = 'portfolios_pic';
    protected $fillable = [
        'portfolio_id',
        'pic'
    ];

    public function getPicAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }

        return null;
    }
}
