<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use ClearsResponseCache;

    protected $table = 'services';
    protected $fillable = [
        'name',
        'icon',
        'desc'
    ];
}
