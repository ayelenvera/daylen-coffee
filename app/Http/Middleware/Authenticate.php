<?php
// app/Http/Middleware/Authenticate.php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }
        
        // ✅ Redirigir al login con mensaje para carrito
        if ($request->is('cart') || $request->is('cart/*')) {
            return route('login', ['message' => 'Por favor inicia sesión para acceder al carrito']);
        }
        
        return route('login');
    }
}