<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'province',
        'district',
        'street',
        'reference',
        'receiver',
        'receiver_info',
        'default',
        'user_id',
    ];

    protected $casts = [
        'receiver_info' => 'array',
        'default' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
