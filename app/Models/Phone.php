<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Phone extends Model
{
    protected $fillable = [
        'prefix',
        'number',
    ];

    public function phoneable(): MorphTo
    {
        return $this->morphTo();
    }
}
