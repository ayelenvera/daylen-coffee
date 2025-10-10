<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\Category;

/**
 * Modelo Product - Representa los productos de la cafetería
 */
class Product extends Model
{

    protected $fillable = [
        'name',
        'description', 
        'price',
        'stock',
        'image',
        'category',
        'has_size',
        'has_toppings',
        'has_addons'
    ];

    protected $appends = ['image_url'];
    
    // Eliminamos los accesores de categoría ya que ahora usamos la relación

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'has_size' => 'boolean',
        'has_toppings' => 'boolean',
        'has_addons' => 'boolean',
    ];

    // Relación con categoría
    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category', 'id')
            ->select(['id', 'name', 'emoji'])  // Seleccionar solo los campos necesarios
            ->withDefault([
                'id' => null,
                'name' => 'Sin categoría',
                'emoji' => '📦'
            ]);
    }
    
    // ✅ RELACIÓN CON PEDIDOS
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class);
    }

    public function toppings(): HasMany
    {
        return $this->hasMany(ProductTopping::class);
    }

    public function addons(): HasMany
    {
        return $this->hasMany(ProductAddon::class);
    }

    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }

    public function getFormattedPriceAttribute(): string
    {
    }

    // Mapa de códigos de emoji a emojis reales según CategoriesTableSeeder
    protected $emojiMap = [
        '0' => '☕',  // Café
        '1' => '🍵',  // Té
        '2' => '🍰',  // Postres
        '3' => '🥐',  // Desayunos
        '4' => '🥗',  // Ensaladas
        '10' => '🧃', // Bebidas
        '6' => '🍫',  // Snacks
        '7' => '🥖',  // Panadería (usando 🥖 baguette)
    ];
    
    // ✅ ACCESOR PARA NOMBRE DE CATEGORÍA
    public function getCategoryNameAttribute()
    {
        $category = Category::where('name', $this->category)->first();
        // Si la categoría tiene un nombre legible, usarlo, de lo contrario usar el valor crudo
        return $category ? $category->name : $this->category;
    }
    
    // ✅ ACCESOR PARA EMOJI DE CATEGORÍA
    public function getCategoryEmojiAttribute()
    {
        $category = Category::where('name', $this->category)->first();
        if ($category) {
            // Si hay una categoría, obtener su emoji (que es un número)
            $emojiCode = $category->emoji;
            // Convertir el número a emoji usando el mapa
            return $this->emojiMap[$emojiCode] ?? '📦'; // Usar 📦 si no se encuentra el código
        }
        return '📦'; // Emoji por defecto si no hay categoría
    }

    /**
     * Get the URL to the product's image.
     */
    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return asset('images/placeholder.jpg');
        }

        // Si es una URL completa, devolverla
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Si es una ruta de storage (nuevo sistema)
        if (strpos($this->image, '/storage/') === 0) {
            return asset($this->image);
        }

        // Para imágenes locales antiguas
        if (strpos($this->image, 'images/') === 0) {
            return asset($this->image);
        }

        // Si es solo un nombre de archivo
        return asset('images/products/' . $this->image);
    }

    /**
     * Handle the file upload and update the image attribute.
     */
    public function uploadImage(?UploadedFile $image)
    {
        if (!$image) {
            return;
        }

        // Delete old image if exists
        $this->deleteImage();

        // Generate a unique filename
        $extension = $image->getClientOriginalExtension();
        $filename = uniqid('product_') . '.' . $extension;

        // Store the image in public/images/products
        $image->move(public_path('images/products'), $filename);

        // Update the image attribute
        $this->image = $filename;
        $this->save();
    }

    /**
     * Delete the product's image from storage.
     */
    public function deleteImage()
    {
        if (!empty($this->image)) {
            $imagePath = public_path('images/products/' . $this->image);
            if (file_exists($imagePath) && !is_dir($imagePath)) {
                unlink($imagePath);
            }
        }
    }

    protected static function booted()
    {
        static::deleting(function ($product) {
            $product->deleteImage();
        });

        static::updating(function ($product) {
            // Si se está actualizando la imagen, eliminar la anterior
            if ($product->isDirty('image') && $product->getOriginal('image')) {
                $oldImagePath = public_path('images/products/' . $product->getOriginal('image'));
                if (file_exists($oldImagePath) && !is_dir($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        });
    }

    /**
     * Relación con promociones activas
     */
    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    /**
     * Obtener la promoción activa actual
     */
    public function activePromotion()
    {
        return $this->hasOne(Promotion::class)->active();
    }

    /**
     * Verificar si el producto tiene promoción activa
     */
    public function getHasActivePromotionAttribute()
    {
        return $this->activePromotion()->exists();
    }

    /**
     * Obtener el precio promocional
     */
    public function getPromotionalPriceAttribute()
    {
        $activePromotion = $this->activePromotion()->first(); // AGREGAR ->first()
    
        if ($activePromotion) {
            return $activePromotion->calculatePromotionalPrice();
        }

        return $this->price;
    }

    /**
     * Obtener el porcentaje de descuento activo
     */
    public function getDiscountPercentageAttribute()
    {
        $activePromotion = $this->activePromotion()->first(); // AGREGAR ->first()
    
        if ($activePromotion) {
            return $activePromotion->discount_percentage;
        }
    
        return 0;
    }

    /**
     * Obtener el ahorro en dinero
     */
    public function getSavingsAmountAttribute()
    {
        if ($this->has_active_promotion) {
            return $this->price - $this->promotional_price;
        }
    
        return 0;
    }
}