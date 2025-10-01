<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shop_name',
        'logo_url',
        'email',
        'phone', 
        'address',
        'ruc',
        'about_us',
        'primary_color',
        'secondary_color',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Obtener la configuración activa de la tienda
     */
    public static function getActiveSettings()
    {
        return static::where('is_active', true)->first() ?? new static([
            'shop_name' => 'Daylen Cafetería',
            'email' => 'daylencoffee@gmail.com',
            'phone' => '+595 986 195914',
            'address' => 'Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este',
            'ruc' => '12345678-9',
            'about_us' => 'Somos una cafetería dedicada a ofrecer los mejores productos con ingredientes de calidad.',
            'primary_color' => '#d97706',
            'secondary_color' => '#f59e0b',
        ]);
    }

    /**
     * Obtener URL del logo o placeholder
     */
    public function getLogoUrlAttribute($value)
    {
        if ($value) {
            // Si es una URL completa
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }
            
            // Si es una ruta relativa
            if (strpos($value, '/') === 0) {
                return url($value);
            }
            
            // Si es solo un nombre de archivo
            return url('/images/logo/' . $value);
        }
        
        return url('/images/placeholder.jpg');
    }

    /**
     * Obtener el About Us con formato por defecto si está vacío
     */
    public function getAboutUsAttribute($value)
    {
        return $value ?: 'Somos una cafetería dedicada a ofrecer los mejores productos con ingredientes de calidad. Nuestro compromiso es brindarte una experiencia única en cada visita.';
    }
}