<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certification extends Model
{
    use SoftDeletes, ClearsResponseCache;

    protected $table = 'certifications';
    protected $guarded = ['created_at', 'updated_at'];
}
