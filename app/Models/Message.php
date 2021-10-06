<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use ClearsResponseCache;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
