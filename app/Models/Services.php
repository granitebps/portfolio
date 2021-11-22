<?php

namespace App\Models;

use App\Traits\ClearsResponseCache;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use ClearsResponseCache;

    protected $table = 'services';
    protected $fillable = [
        'name',
        'icon',
        'desc'
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ServiceFactory::new();
    }
}
