<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Technology extends Model
{
    use ClearsResponseCache;

    protected $table = 'technologies';
    protected $fillable = [
        'name',
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
