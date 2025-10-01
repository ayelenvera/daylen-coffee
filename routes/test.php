<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Test route is working!',
        'session' => session()->all(),
        'cart' => class_exists('Gloudemans\\Shoppingcart\\Facades\\Cart') ? 'Cart class exists' : 'Cart class not found'
    ]);
});
