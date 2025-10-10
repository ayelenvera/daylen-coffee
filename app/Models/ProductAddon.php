<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAddon extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'has_quantity'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'has_quantity' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
