<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
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