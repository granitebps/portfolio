<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use ClearsResponseCache;

    protected $table = 'educations';
    protected $fillable = [
        'name',
        'institute',
        'start_year',
        'end_year',
    ];
}
