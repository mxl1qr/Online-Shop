<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size',
        'color',
        'stock',
        'sku',
        'additional_price',
    ];

    // Varian ini milik produk apa
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
