<?php

namespace App\Http\Middleware;

use App\Models\ShopSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShareShopSettings
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Obtener configuración activa de la tienda
            $shopSettings = ShopSetting::where('is_active', true)->first();
            
            if (!$shopSettings) {
                $shopSettings = new ShopSetting([
                    'shop_name' => 'Daylen Cafetería',
                    'email' => 'daylencoffee@gmail.com',
                    'phone' => '+595 986 195914',
                    'address' => 'Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este',
                    'ruc' => '12345678-9',
                    'about_us' => 'Somos una cafetería dedicada a ofrecer los mejores productos con ingredientes de calidad.',
                    'primary_color' => '#d97706',
                    'secondary_color' => '#f59e0b',
                    'is_active' => true,
                ]);
            }
            
            $settingsData = [
                'id' => $shopSettings->id ?? null,
                'shop_name' => $shopSettings->shop_name,
                'logo_url' => $shopSettings->getLogoUrlAttribute($shopSettings->logo_url ?? null),
                'email' => $shopSettings->email,
                'phone' => $shopSettings->phone,
                'address' => $shopSettings->address,
                'ruc' => $shopSettings->ruc,
                'about_us' => $shopSettings->about_us,
                'primary_color' => $shopSettings->primary_color,
                'secondary_color' => $shopSettings->secondary_color,
                'is_active' => $shopSettings->is_active ?? true,
                'updated_at' => $shopSettings->updated_at?->toISOString(),
            ];

            // Compartir con Inertia
            if ($request->header('X-Inertia')) {
                inertia()->share(['shopSettings' => $settingsData]);
            }
            
            view()->share('shopSettings', (object) $settingsData);
            
        } catch (\Exception $e) {
            // Fallback silencioso
            $defaultSettings = [
                'shop_name' => 'Daylen Cafetería',
                'logo_url' => url('/images/placeholder.jpg'),
                'email' => 'daylencoffee@gmail.com',
                'phone' => '+595 986 195914',
                'address' => 'Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este',
                'ruc' => '12345678-9',
                'about_us' => 'Somos una cafetería dedicada a ofrecer los mejores productos con ingredientes de calidad.',
                'primary_color' => '#d97706',
                'secondary_color' => '#f59e0b',
                'is_active' => true,
            ];
            
            if ($request->header('X-Inertia')) {
                inertia()->share(['shopSettings' => $defaultSettings]);
            }
            view()->share('shopSettings', (object) $defaultSettings);
        }

        return $next($request);
    }
}