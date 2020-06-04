<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $guarded = ['created_at', 'updated_at'];
    protected $table = 'educations';
}
