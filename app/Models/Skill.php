<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Database\Factories\SkillFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use ClearsResponseCache, HasFactory;

    protected $table = 'skills';
    protected $fillable = [
        'name',
        'percentage'
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return SkillFactory::new();
    }
}
