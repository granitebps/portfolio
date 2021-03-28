<?php

namespace App\Models\ViewModels;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use ClearsResponseCache;

    protected $table = 'vm_logs';
    protected $fillable = [
        'url',
        'date',
        'count'
    ];
    public $timestamps = false;
}
