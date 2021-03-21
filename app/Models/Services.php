<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use ClearsResponseCache;

    protected $table = 'services';
    protected $guarded = ['created_at', 'updated_at'];
}
