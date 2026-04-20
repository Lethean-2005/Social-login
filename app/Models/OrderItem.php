<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'unit_price_cents',
        'quantity',
        'line_total_cents',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedUnitPriceAttribute(): string
    {
        return '$'.number_format($this->unit_price_cents / 100, 2);
    }

    public function getFormattedLineTotalAttribute(): string
    {
        return '$'.number_format($this->line_total_cents / 100, 2);
    }
}
