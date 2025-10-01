<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'emoji', 'is_active'];
    
    protected $casts = [
        'emoji' => 'integer', // Asegura que el emoji se maneje como entero
        'is_active' => 'boolean',
    ];
    
    protected $attributes = [
        'is_active' => true, // Valor por defecto
    ];

    // Lista de emojis disponibles (debe coincidir con el array de Vue)
    public static function getAvailableEmojis()
    {
        return [
            '☕', '🍵', '🍰', '🥐', '🥗', '🧃', '🍫', '🍩', '🍪', '🥤', 
            '🍷', '🍺', '🥂', '🍹', '🍸', '🥃', '🍾', '🍶', '🍼', '🍻', 
            '🧋', '🧉', '🧊', '🍽️', '🍴', '🥄', '🔪', '🏺', '📦'
        ];
    }

    // Mutador para guardar el índice del emoji
    public function setEmojiAttribute($value)
    {
        if (is_numeric($value)) {
            $this->attributes['emoji'] = (int)$value;
            return;
        }
        
        $emojis = self::getAvailableEmojis();
        $index = array_search($value, $emojis);
        $this->attributes['emoji'] = $index !== false ? $index : 0;
    }
    
    // Método para obtener el índice del emoji actual
    public function getEmojiIndexAttribute()
    {
        return $this->attributes['emoji'] ?? 0;
    }
    
    // Accesor para is_active
    public function getIsActiveAttribute($value)
    {
        return (bool) $value;
    }

    // ✅ RELACIÓN CON PRODUCTOS
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category', 'id');
    }

    // ✅ SCOPE PARA CATEGORÍAS ACTIVAS (siempre activas)
    public function scopeActive($query)
    {
        return $query; // No filtrar, todas están activas
    }
}