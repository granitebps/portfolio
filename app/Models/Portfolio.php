<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Portfolio extends Model
{
    use SoftDeletes, ClearsResponseCache;

    protected $table = 'portfolios';
    protected $fillable = [
        'name',
        'desc',
        'thumbnail',
        'type',
        'url',
    ];

    protected $casts = [
        'type' => 'integer',
    ];

    public function pic()
    {
        return $this->hasMany(PortfolioPic::class, 'portfolio_id', 'id');
    }

    public function getThumbnailAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }
        return null;
    }

    public function getUrlAttribute($value)
    {
        if (is_null($value)) {
            return '';
        }
        return $value;
    }
}
