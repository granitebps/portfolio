<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'vm_logs';
    protected $fillable = [
        'url',
        'date',
        'count'
    ];
    public $timestamps = false;
}
