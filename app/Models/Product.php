<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'stock',
        'is_new',
        'is_bestseller',
        'is_active',
    ];

    /* ================= Relations ================= */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /* ================= Helpers ================= */

    public function getFinalPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }
}
