<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Http\Controllers\Web\ProductController as WebProductController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ShopSettingsController;

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Product Routes - Public
Route::get('/products', [WebProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [WebProductController::class, 'show'])->name('products.show');

Route::get('/verify-mysql-time', function() {
    $timeCheck = \DB::select("
        SELECT 
            NOW() as mysql_now,
            @@global.time_zone as global_tz,
            @@session.time_zone as session_tz
    ")[0];
    
    return [
        'mysql_time_after_restart' => $timeCheck->mysql_now,
        'global_timezone' => $timeCheck->global_tz,
        'session_timezone' => $timeCheck->session_tz,
        'laravel_time' => now()->format('Y-m-d H:i:s'),
        'should_match' => $timeCheck->mysql_now === now()->format('Y-m-d H:i:s'),
    ];
});

// Rutas de Ayuda - Accesibles para todos
Route::get('/help', function () {
    return Inertia::render('Help/Index');
})->name('help');

Route::get('/help/user-guide', function () {
    return Inertia::render('Help/UserGuide');
})->name('help.user-guide');

Route::get('/help/admin-guide', function () {
    // Solo administradores pueden acceder
    if (!auth()->check() || !auth()->user()->is_admin) {
        abort(403, 'Acceso no autorizado');
    }
    return Inertia::render('Help/AdminGuide');
})->name('help.admin-guide');

// Cart Routes - ✅ PROTEGER con middleware auth
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', function () {
        if (auth()->user()->is_admin) {
            return redirect()->route('home')->with('error', 'Los administradores no pueden acceder al carrito');
        }
        
        return Inertia::render('Cart', [
            'cart' => session('cart', [])
        ]);
    })->name('cart');

    // Carrito: agregar artículo
    Route::post('/cart/add', function (\Illuminate\Http\Request $request) {
        if (auth()->user()->is_admin) {
            return back()->with('error', 'Los administradores no pueden usar el carrito');
        }
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);
        $qty = (int)($validated['quantity'] ?? 1);
        $product = \App\Models\Product::find($validated['product_id']);
        if (!$product || $product->stock <= 0) {
            return back()->with('error', 'Producto sin stock');
        }

        $cart = session('cart', [
            'items' => [],
            'total' => 0,
            'item_count' => 0,
        ]);

        $key = (string)$product->id;
        if (!isset($cart['items'][$key])) {
            // Normalizar imagen como en /home
            $image = $product->image;
            if (!$image) {
                $image = url('/images/placeholder.jpg');
            } elseif (Str::startsWith($image, ['http://', 'https://'])) {
                // keep
            } elseif (Str::startsWith($image, ['/'])) {
                $image = url($image);
            } elseif (Str::startsWith($image, ['images/'])) {
                $image = url('/'.ltrim($image, '/'));
            } elseif (Str::startsWith($image, ['public/'])) {
                $image = url('/'.ltrim(Str::after($image, 'public/'), '/'));
            } elseif (Str::contains($image, '/')) {
                $image = url('/storage/'.ltrim($image, '/'));
            } else {
                $image = url('/images/products/'.ltrim($image, '/'));
            }
            // ✅ USAR PRECIO PROMOCIONAL SI TIENE PROMOCIÓN ACTIVA
            $finalPrice = $product->has_active_promotion ? (float)$product->promotional_price : (float)$product->price;

            $cart['items'][$key] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $finalPrice, // ✅ PRECIO CORRECTO (promocional o normal)
                'original_price' => (float)$product->price, // ✅ GUARDAR PRECIO ORIGINAL
                'discount_percentage' => $product->has_active_promotion ? $product->discount_percentage : 0, // ✅ % DESCUENTO
                'has_promotion' => $product->has_active_promotion, // ✅ INDICAR SI TIENE PROMOCIÓN
                'image' => $image,
                'quantity' => 0,
                'subtotal' => 0,
            ];
        }
        $cart['items'][$key]['quantity'] += $qty;
        $cart['items'][$key]['subtotal'] = $cart['items'][$key]['quantity'] * $cart['items'][$key]['price'];

        // Recalcular totales
        $cart['total'] = 0; $cart['item_count'] = 0;
        foreach ($cart['items'] as $it) {
            $cart['total'] += $it['subtotal'];
            $cart['item_count'] += $it['quantity'];
        }
        session(['cart' => $cart]);

        return back()->with('success', 'Producto agregado al carrito');
    })->name('cart.add');

    // Carrito: quitar artículo
    Route::post('/cart/remove', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'product_id' => 'required|integer',
        ]);
        $cart = session('cart', [ 'items' => [], 'total' => 0, 'item_count' => 0 ]);
        $key = (string)$validated['product_id'];
        if (isset($cart['items'][$key])) {
            unset($cart['items'][$key]);
            // Recalcular
            $cart['total'] = 0; $cart['item_count'] = 0;
            foreach ($cart['items'] as $it) {
                $cart['total'] += $it['subtotal'];
                $cart['item_count'] += $it['quantity'];
            }
            session(['cart' => $cart]);
        }
        return back();
    })->name('cart.remove');

    // Carrito: actualizar cantidad y opciones
    Route::post('/cart/update', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'nullable|integer|min:1',
            'size' => 'nullable|string|max:50',
            'sugar' => 'nullable|string|max:50',
        ]);
        $cart = session('cart', [ 'items' => [], 'total' => 0, 'item_count' => 0 ]);
        $key = (string)$validated['product_id'];
        if (!isset($cart['items'][$key])) {
            return back();
        }
        if (isset($validated['quantity'])) {
            $cart['items'][$key]['quantity'] = (int)$validated['quantity'];
            $cart['items'][$key]['subtotal'] = $cart['items'][$key]['quantity'] * $cart['items'][$key]['price'];
        }
        if (isset($validated['size'])) {
            $cart['items'][$key]['size'] = $validated['size'];
        }
        if (isset($validated['sugar'])) {
            $cart['items'][$key]['sugar'] = $validated['sugar'];
        }
        // Recalcular
        $cart['total'] = 0; $cart['item_count'] = 0;
        foreach ($cart['items'] as $it) {
            $cart['total'] += $it['subtotal'];
            $cart['item_count'] += $it['quantity'];
        }
        session(['cart' => $cart]);
        return back();
    })->name('cart.update');

    // Carrito: limpiar
    Route::delete('/cart/clear', function () {
        session(['cart' => [
            'items' => [],
            'total' => 0,
            'item_count' => 0,
        ]]);
        return back();
    })->name('cart.clear');

    // Checkout - mostrar formulario
    Route::get('/checkout', function () {
        if (auth()->user()->is_admin) {
            return redirect()->route('home');
        }
        return Inertia::render('CheckoutForm', [
            'cart' => session('cart', [ 'items' => [], 'total' => 0, 'item_count' => 0 ])
        ]);
    })->name('checkout');

    // Checkout - crear pedido
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout.store');
    
    // Factura del pedido
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
});

// Test Database Connection
Route::get('/test-db', function() {
    try {
        DB::connection()->getPdo();
        return response()->json([
            'status' => 'success',
            'message' => 'Database connection successful!',
            'database' => DB::connection()->getDatabaseName()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Could not connect to the database.',
            'error' => $e->getMessage()
        ]);
    }
});

// Authentication Routes
require __DIR__.'/auth.php';

// Authenticated User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    Route::get('/orders/{order}/download-invoice', [OrderController::class, 'downloadInvoice'])->name('orders.download-invoice');
    // Order Routes - NUEVAS RUTAS DE ELIMINACIÓN
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('/orders/bulk-delete', [OrderController::class, 'bulkDelete'])->name('orders.bulk-delete');
    Route::post('/orders/delete-all', [OrderController::class, 'deleteAll'])->name('orders.delete-all');
    Route::post('/orders/restore', [OrderController::class, 'restore'])->name('orders.restore');
});

// Admin Routes - RUTAS ACTUALIZADAS para pedidos
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Rutas de categorías
    Route::apiResource('categories', AdminCategoryController::class)
        ->except(['index', 'show', 'create', 'edit'])
        ->names('admin.categories');
        
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // ✅ RUTAS DE PRODUCTOS COMPLETAS - AGREGAR LAS FALTANTES
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::post('/products', [AdminController::class, 'store'])->name('admin.products.store');
    Route::put('/products/{product}', [AdminController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroy'])->name('admin.products.destroy');
    Route::post('/products/restore', [AdminController::class, 'restore'])->name('admin.products.restore');
    
    // ✅ AGREGAR ESTA RUTA FALTANTE PARA BULK DELETE
    Route::post('/products/bulk-delete', [AdminController::class, 'bulkDelete'])->name('admin.products.bulk-delete');
    Route::post('/products/delete-all', [AdminController::class, 'deleteAll'])->name('admin.products.delete-all');

    // RUTAS DE PEDIDOS
    Route::get('/orders', [OrdersController::class, 'index'])->name('admin.orders');
    Route::patch('/orders/{order}/status', [OrdersController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::delete('/orders/{order}', [OrdersController::class, 'destroy'])->name('admin.orders.destroy');
    Route::post('/orders/bulk-delete', [OrdersController::class, 'bulkDelete'])->name('admin.orders.bulk-delete');
    Route::post('/orders/delete-all', [OrdersController::class, 'deleteAll'])->name('admin.orders.delete-all');
    Route::post('/orders/restore', [OrdersController::class, 'restore'])->name('admin.orders.restore');
    Route::get('/orders/{order}/invoice', [OrdersController::class, 'adminInvoice'])->name('admin.orders.invoice');
    
    // Otras rutas de administración
    Route::get('/product-customization', function () {
        return Inertia::render('Admin/ProductCustomization', [
            'products' => \App\Models\Product::with(['sizes', 'toppings', 'addons'])->get()
        ]);
    })->name('admin.product-customization');
    
    Route::get('/stock-management', function () {
        return Inertia::render('Admin/StockManagement', [
            'products' => \App\Models\Product::all()
        ]);
    })->name('admin.stock-management');

    // Rutas de promociones
    Route::get('/promotions', [AdminController::class, 'promotions'])->name('admin.promotions');
    Route::post('/promotions/add', [AdminController::class, 'addPromotion'])->name('admin.promotions.add');
    Route::post('/promotions/{promotion}/update', [AdminController::class, 'updatePromotion'])->name('admin.promotions.update');
    Route::delete('/promotions/{promotion}', [AdminController::class, 'removePromotion'])->name('admin.promotions.remove');

    // Rutas para reglas de personalización - CORREGIDAS
    Route::get('/customization-rules', [App\Http\Controllers\Admin\CustomizationRulesController::class, 'index'])->name('admin.customization-rules');
    Route::post('/customization-rules', [App\Http\Controllers\Admin\CustomizationRulesController::class, 'store'])->name('admin.customization-rules.store');
    Route::put('/customization-rules/{customizationRule}', [App\Http\Controllers\Admin\CustomizationRulesController::class, 'update'])->name('admin.customization-rules.update');
    Route::delete('/customization-rules/{customizationRule}', [App\Http\Controllers\Admin\CustomizationRulesController::class, 'destroy'])->name('admin.customization-rules.destroy');
    
    // ✅ NUEVAS RUTAS PARA CONFIGURACIÓN DE TIENDA
    Route::get('/shop-settings', [ShopSettingsController::class, 'index'])->name('admin.shop-settings');
    Route::post('/shop-settings', [ShopSettingsController::class, 'update'])->name('admin.shop-settings.update');
    Route::get('/api/shop-settings', [ShopSettingsController::class, 'getSettings'])->name('admin.shop-settings.get');
});

// Ruta de forgot-password
Route::get('/forgot-password', function () {
    return Inertia::render('Auth/ForgotPassword');
})->name('password.request');

// Ruta de éxito después del restablecimiento de contraseña
Route::get('/password-reset-success', function () {
    return Inertia::render('Auth/PasswordResetSuccess', [
        'user' => auth()->user()
    ]);
})->name('password.reset.success');