<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Family extends Model
{
    protected $fillable = [
        'name'
    ];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
