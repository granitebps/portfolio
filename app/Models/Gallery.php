<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use ClearsResponseCache;

    const IMAGE_EXT = ['png', 'jpg', 'jpeg'];

    protected $table = 'galeries';
    protected $fillable = ['name', 'ext', 'size'];
    protected $hidden = ['updated_at'];
}
