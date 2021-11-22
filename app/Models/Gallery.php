<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Database\Factories\GalleryFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use ClearsResponseCache, HasFactory;

    const IMAGE_EXT = ['png', 'jpg', 'jpeg'];

    protected $table = 'galeries';
    protected $fillable = ['name', 'ext', 'size', 'file'];
    protected $hidden = ['updated_at'];

    public function getFileAttribute($value): string
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
        return GalleryFactory::new();
    }
}
