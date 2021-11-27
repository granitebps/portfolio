<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Database\Factories\PortfolioFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Portfolio extends Model
{
    use SoftDeletes, ClearsResponseCache, HasFactory;

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

    public function pic(): HasMany
    {
        return $this->hasMany(PortfolioPic::class, 'portfolio_id', 'id');
    }

    public function getThumbnailAttribute(string|null $value): string
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
        return PortfolioFactory::new();
    }
}
