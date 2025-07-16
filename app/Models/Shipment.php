<?php

namespace App\Models;

use App\Enums\ShipmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = [
        'tracking_number',
        'status',
        'shipped_at',
        'delivered_at',
        'refunded_at',
        'order_id',
        'shipping_companies_id',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'refunded_at' => 'datetime',
        'status' => ShipmentStatus::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /* Deberia ser shippingCompany pero livewire no lo acepta */
    public function shippingCompanies(): BelongsTo
    {
        return $this->belongsTo(ShippingCompany::class);
    }
}
