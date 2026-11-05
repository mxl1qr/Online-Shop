<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'base_price',
        'image',
        'images',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    // Produk milik suatu Kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // 1 Produk memiliki banyak varian (Ukuran/Warna)
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Foto kedua untuk efek hover ala Uniqlo & H&M
    public function getSecondaryImageAttribute(): ?string
    {
        if (!empty($this->images) && is_array($this->images) && count($this->images) > 1) {
            return $this->images[1];
        }
        if (!$this->image) return null;
        $secondary = str_replace('-1.jpg', '-2.jpg', $this->image);
        return file_exists(storage_path('app/public/' . $secondary)) ? $secondary : $this->image;
    }

    // Seluruh foto galeri lookbook pakaian (termasuk slide 1, 2, dst)
    public function getGalleryImagesAttribute(): array
    {
        if (!empty($this->images) && is_array($this->images) && count($this->images) > 0) {
            return $this->images;
        }

        $list = [];
        if ($this->image) {
            $list[] = $this->image;
            $secondary = str_replace('-1.jpg', '-2.jpg', $this->image);
            if (file_exists(storage_path('app/public/' . $secondary)) && $secondary !== $this->image) {
                $list[] = $secondary;
            }
        }
        return $list;
    }
}
