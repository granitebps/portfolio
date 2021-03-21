<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use ClearsResponseCache;

    protected $guarded = ['created_at', 'updated_at'];
}
