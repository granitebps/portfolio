<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Database\Factories\TechnologyFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Technology extends Model
{
    use ClearsResponseCache, HasFactory;

    protected $table = 'technologies';
    protected $fillable = [
        'name',
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
        return TechnologyFactory::new();
    }
}
