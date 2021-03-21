<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use ClearsResponseCache;

    protected $table = 'galeries';
    protected $fillable = ['image'];
}
