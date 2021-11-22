<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Database\Factories\ExperienceFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use ClearsResponseCache, HasFactory;

    protected $table = 'experiences';
    protected $fillable = [
        'company',
        'position',
        'start_date',
        'end_date',
        'current_job',
        'desc'
    ];

    protected $casts = [
        'current_job' => 'boolean',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ExperienceFactory::new();
    }
}
