<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Share common data for all views
     * 
     * @return void
     */
    protected function shareCommonData()
    {
        // Share cart count if user is authenticated
        if (auth()->check()) {
            \Inertia\Inertia::share([
                'auth' => [
                    'user' => [
                        'id' => auth()->id(),
                        'name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                        'is_admin' => auth()->user()->is_admin ?? false,
                    ],
                ],
                'cartCount' => \App\Services\CartService::getCartData()['count'] ?? 0,
                'flash' => [
                    'success' => session('success'),
                    'error' => session('error'),
                    'message' => session('message'),
                ],
            ]);
        } else {
            \Inertia\Inertia::share([
                'auth' => [
                    'user' => null,
                ],
                'cartCount' => 0,
                'flash' => [
                    'success' => session('success'),
                    'error' => session('error'),
                    'message' => session('message'),
                ],
            ]);
        }
    }
}
