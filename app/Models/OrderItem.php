<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo OrderItem - Representa los items individuales de un pedido
 * 
 * Campos:
 * - order_id: ID del pedido al que pertenece
 * - product_id: ID del producto
 * - quantity: Cantidad del producto
 * - subtotal: Subtotal (precio * cantidad)
 */
class OrderItem extends Model
{
    /**
     * Campos que se pueden asignar masivamente
     */
    protected $fillable = [
        'order_id',
        'product_id', 
        'quantity',
        'subtotal',
        'price',
        'size',
        'sugar',
        'toppings',  // ← AGREGAR
        'addons'     // ← AGREGAR
    ];
    
    protected $casts = [
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
        'price' => 'decimal:2',
        'toppings' => 'array',  // ← AGREGAR
        'addons' => 'array'     // ← AGREGAR
    ];

    /**
     * Relación con Order - Un item pertenece a un pedido
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación con Product - Un item pertenece a un producto
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calcular subtotal automáticamente
     */
    public function calculateSubtotal(): float
    {
        return $this->quantity * $this->product->price;
    }

    /**
     * Formatear subtotal para mostrar
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return '$' . number_format($this->subtotal, 2);
    }
}
