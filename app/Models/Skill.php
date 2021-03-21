<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use ClearsResponseCache;

    protected $table = 'skills';
    protected $guarded = ['created_at', 'updated_at'];
}
