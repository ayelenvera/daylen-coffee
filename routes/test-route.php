<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-route', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Test route is working!',
        'app_env' => env('APP_ENV', 'not set'),
        'app_debug' => env('APP_DEBUG', 'not set'),
        'vite_hmr' => env('VITE_HMR', 'not set'),
    ]);
});
