<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Database\Factories\PortfolioPicFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class PortfolioPic extends Model
{
    use SoftDeletes, ClearsResponseCache, HasFactory;

    protected $table = 'portfolios_pic';
    protected $fillable = [
        'portfolio_id',
        'pic'
    ];

    public function getPicAttribute(string|null $value): string
    {
        if ($value) {
            return Storage::url($value);
        }

        return '';
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return PortfolioPicFactory::new();
    }
}
