<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    use ClearsResponseCache;

    protected $table = 'technologies';
    protected $guarded = ['created_at', 'updated_at'];
}
