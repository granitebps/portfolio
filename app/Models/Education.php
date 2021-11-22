<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Database\Factories\EducationFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
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

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return EducationFactory::new();
    }
}
