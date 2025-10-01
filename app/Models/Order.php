<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Order - Representa los pedidos de la cafetería
 * 
 * Campos:
 * - user_id: ID del usuario que hizo el pedido
 * - total: Total del pedido con 2 decimales
 * - status: Estado del pedido (pending, paid, cancelled)
 * - payment_id: ID del pago (opcional)
 */
class Order extends Model
{
    /**
     * Campos que se pueden asignar masivamente
     */
    protected $fillable = [
        'user_id',
        'total', 
        'status',
        'payment_id',
        'customer_name',  // ← AGREGAR
        'phone',          // ← AGREGAR
        'address',        // ← AGREGAR
        'payment_method', // ← AGREGAR
        'notes'           // ← AGREGAR
    ];

    /**
     * Estado por defecto del pedido
     */
    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    /**
     * Casts para los campos
     */
    protected $casts = [
        'total' => 'decimal:2',
    ];

    /**
     * Relación con el usuario que hizo el pedido
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con OrderItem - Un pedido tiene múltiples items
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Estados posibles del pedido
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Verificar si el pedido está pagado
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Formatear total para mostrar
     */
    public function getFormattedTotalAttribute(): string
    {
        return '$' . number_format($this->total, 2);
    }

}
