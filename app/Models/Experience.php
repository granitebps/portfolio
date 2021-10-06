<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use ClearsResponseCache;

    protected $table = 'experiences';
    protected $fillable = [
        'company',
        'position',
        'start_date',
        'end_date',
        'current_job',
        'desc'
    ];
}
