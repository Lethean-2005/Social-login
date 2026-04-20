<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUSES = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];

    protected $fillable = [
        'user_id',
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'notes',
        'subtotal_cents',
        'total_cents',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return '$'.number_format($this->total_cents / 100, 2);
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return '$'.number_format($this->subtotal_cents / 100, 2);
    }

    public function getStatusColorAttribute(): string
    {
        return [
            'pending' => 'bg-amber-50 text-amber-700',
            'paid' => 'bg-blue-50 text-blue-700',
            'shipped' => 'bg-indigo-50 text-indigo-700',
            'completed' => 'bg-green-50 text-green-700',
            'cancelled' => 'bg-red-50 text-red-700',
        ][$this->status] ?? 'bg-gray-100 text-gray-700';
    }
}
