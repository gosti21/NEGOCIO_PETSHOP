<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ShippingCompany extends Model
{
    protected $fillable = [
        'name',
        'email',
        'district',
        'street',
        'reference'
    ];

    public function phones(): MorphOne
    {
        return $this->morphOne(Phone::class, 'phoneable');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class)->chaperone();
    }
}
