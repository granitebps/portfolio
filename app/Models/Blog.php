<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Blog extends Model
{
    use SoftDeletes, ClearsResponseCache;

    protected $table = 'blogs';
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getImageAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }
        return null;
    }
}
