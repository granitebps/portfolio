<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Database\Factories\CertificationFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certification extends Model
{
    use SoftDeletes, ClearsResponseCache;

    protected $table = 'certifications';
    protected $fillable = [
        'name',
        'institution',
        'link',
        'published'
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return CertificationFactory::new();
    }
}
