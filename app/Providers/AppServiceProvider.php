<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // ✅ CONFIGURACIÓN CRÍTICA PARA RAILWAY
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
            
            // Trust proxies de Railway
            $this->app['request']->setTrustedProxies(
                ['127.0.0.1', 'REMOTE_ADDR'],
                \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO
            );
        }

        // Solo compartir el usuario si hay conexión a la base de datos
        View::composer('*', function ($view) {
            try {
                $view->with('auth', [
                    'user' => auth()->user()
                ]);
            } catch (\Exception $e) {
                // Si hay error de base de datos, pasar usuario null
                $view->with('auth', [
                    'user' => null
                ]);
            }
        });
    }
}