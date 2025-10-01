<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Services\CartService;
use App\Http\Controllers\Api\GoogleMapsController;

// ===== RUTAS DE AUTENTICACIÓN API =====
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1'); // 5 intentos por minuto

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware(['auth:sanctum', 'throttle:60,1']); // 60 peticiones por minuto

Route::get('/user', [AuthController::class, 'user'])
    ->middleware(['auth:sanctum', 'throttle:60,1']);

// ===== RUTAS DE CATEGORÍAS =====
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('categories', \App\Http\Controllers\Admin\CategoryController::class)
        ->except(['index', 'show', 'create', 'edit']);
});

// ===== RUTAS PÚBLICAS =====
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando 🚀']);
});

Route::get('/test-db', function() {
    try {
        DB::connection()->getPdo();
        return response()->json([
            'status' => 'success',
            'message' => 'Database connection successful!',
            'database' => DB::connection()->getDatabaseName(),
            'tables' => DB::select('SELECT name FROM sqlite_master WHERE type="table"')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Could not connect to the database.',
            'error' => $e->getMessage(),
            'db_path' => database_path('database.sqlite'),
            'file_exists' => file_exists(database_path('database.sqlite')),
            'is_readable' => is_readable(database_path('database.sqlite')),
            'is_writable' => is_writable(database_path('database.sqlite')),
        ]);
    }
});

// Rutas de productos públicas con caché
Route::middleware('throttle:100,1')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/{product}/customization-rules', function ($productId) {
        try {
            // Buscar el producto
            $product = Product::with('categoryRelation')->find($productId);
            
            if (!$product) {
                return response()->json([
                    'error' => 'Producto no encontrado'
                ], 404);
            }
    
            // Obtener las reglas de personalización usando el CartService
            $rules = CartService::getProductCustomizationRules($productId);
            
            return response()->json($rules);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cargar reglas de personalización: ' . $e->getMessage()
            ], 500);
        }
    });
});

// ===== RUTAS PROTEGIDAS =====
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // Rutas del carrito
    /*Route::post('/cart/add', [CartController::class, 'add']);
    Route::post('/cart/remove', [CartController::class, 'remove']);
    Route::get('/cart', [CartController::class, 'view']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);*/

    Route::prefix('maps')->group(function () {
        Route::get('/autocomplete', [GoogleMapsController::class, 'autocomplete']);
        Route::get('/place-details', [GoogleMapsController::class, 'placeDetails']);
        Route::get('/reverse-geocode', [GoogleMapsController::class, 'reverseGeocode']);
        Route::get('/geocode', [GoogleMapsController::class, 'geocode']);
        Route::get('/calculate-delivery', [GoogleMapsController::class, 'calculateDelivery']);
    });

    // Rutas de pedidos
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/checkout', [OrderController::class, 'checkout']);
});

// ===== RUTAS DE ADMINISTRADOR =====
/*  Route::middleware(['auth:sanctum', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/stats', [AdminController::class, 'stats']);
    
    // CRUD de productos
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Gestión de pedidos
    Route::get('/orders', [OrderController::class, 'adminIndex']);
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);
});*/