<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'status',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_notes',
        'payment_method',
    ];

    // Order milik satu User (Pembeli)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 1 Order memiliki banyak rincian barang (Order Items)
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}