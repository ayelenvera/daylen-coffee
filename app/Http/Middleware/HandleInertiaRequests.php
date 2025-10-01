<?php
// app/Http/Middleware/HandleInertiaRequests.php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'is_admin' => (bool) ($request->user()->is_admin ?? false),
                ] : null,
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'shopSettings' => function () {
                try {
                    $shopSettings = \App\Models\ShopSetting::where('is_active', true)->first();
                    
                    if (!$shopSettings) {
                        return [
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
                    }
                    
                    return [
                        'id' => $shopSettings->id,
                        'shop_name' => $shopSettings->shop_name,
                        'logo_url' => $shopSettings->getLogoUrlAttribute($shopSettings->logo_url),
                        'email' => $shopSettings->email,
                        'phone' => $shopSettings->phone,
                        'address' => $shopSettings->address,
                        'ruc' => $shopSettings->ruc,
                        'about_us' => $shopSettings->about_us,
                        'primary_color' => $shopSettings->primary_color,
                        'secondary_color' => $shopSettings->secondary_color,
                        'is_active' => $shopSettings->is_active,
                        'updated_at' => $shopSettings->updated_at?->toISOString(),
                    ];
                } catch (\Exception $e) {
                    return [
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
                }
            },
        ]);
    }
}