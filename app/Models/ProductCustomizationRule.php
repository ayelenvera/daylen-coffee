<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustomizationRule extends Model
{
    protected $fillable = [
        'category_id', 
        'enabled_options',
        'size_options',
        'sugar_options',
        'topping_options',    // ← AGREGAR ESTO
        'addon_options',      // ← AGREGAR ESTO  
        'default_size',       // ← Y ESTO
        'excluded_products'
    ];

    protected $casts = [
        'enabled_options' => 'array',
        'size_options' => 'array',
        'sugar_options' => 'array',
        'topping_options' => 'array',
        'addon_options' => 'array',
        'excluded_products' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}