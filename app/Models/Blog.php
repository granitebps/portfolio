<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Database\Factories\BlogFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Blog extends Model
{
    use SoftDeletes, ClearsResponseCache, HasFactory;

    protected $table = 'blogs';
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
        'image',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getImageAttribute(string|null $value): string
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
        return BlogFactory::new();
    }
}
