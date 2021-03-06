<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use ClearsResponseCache;

    protected $table = 'experiences';
    protected $guarded = ['created_at', 'updated_at'];
}
