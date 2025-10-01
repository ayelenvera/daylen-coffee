<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Promotion extends Model
{
    protected $fillable = [
        'product_id',
        'discount_percentage', 
        'original_price',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'original_price' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    protected $appends = ['is_currently_active'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Scope para promociones activas - CON COMPENSACIÓN
     */
    public function scopeActive($query)
    {
        $now = Carbon::now('America/Asuncion')->addHour(); // ✅ SUMAR 1 hora
    
        return $query->where('is_active', true)
                    ->where(function($q) use ($now) {
                        $q->whereNull('start_date')
                          ->orWhere('start_date', '<=', $now);
                    })
                    ->where(function($q) use ($now) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', $now);
                    });
    }

    /**
     * Accessor para verificar si la promoción está activa - CON COMPENSACIÓN
     */
    public function getIsCurrentlyActiveAttribute()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now('America/Asuncion')->addHour(); // ✅ SUMAR 1 hora

        if ($this->start_date && $this->start_date->gt($now)) {
            return false;
        }

        if ($this->end_date && $this->end_date->lt($now)) {
            return false;
        }

        return true;
    }

    /**
     * Calcular precio promocional
     */
    public function calculatePromotionalPrice(): float
    {
        return round($this->original_price * (1 - ($this->discount_percentage / 100)), 2);
    }

    /**
     * Scope para productos con promociones activas
     */
    public function scopeWithActivePromotions($query)
    {
        return $query->whereHas('promotions', function($q) {
            $q->active();
        });
    }
}