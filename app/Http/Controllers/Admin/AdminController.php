<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Promotion;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    protected function checkAdmin()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Acceso no autorizado');
        }
    }

    public function dashboard()
    {
        $this->checkAdmin();

        $stats = [
            'total_orders' => Order::count(),
            'total_customers' => User::where('is_admin', false)->count(),
            'total_products' => Product::count(),
            'revenue' => Order::where('status', 'paid')->sum('total')
        ];

        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get();

        $newOrdersCount = Order::where('created_at', '>=', now()->subDay())->count();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'newOrdersCount' => $newOrdersCount
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $this->checkAdmin();

        ini_set('upload_max_filesize', '10M');
        ini_set('post_max_size', '10M');
        ini_set('max_execution_time', '300');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:10240',
        ]);

        $product->name = $validated['name'];
        $product->description = $validated['description'] ?? null;
        $product->price = $validated['price'];
        $product->stock = $validated['stock'];
        
        if (isset($validated['category'])) {
            $category = \App\Models\Category::find($validated['category']);
            if ($category) {
                $product->category = $category->id;
            }
        }

        if ($request->hasFile('image')) {
            try {
                \Log::info('Archivo recibido', [
                    'name' => $request->file('image')->getClientOriginalName(),
                    'size' => $request->file('image')->getSize(),
                    'mime' => $request->file('image')->getMimeType(),
                    'error' => $request->file('image')->getError()
                ]);
                
                $dir = public_path('images/products');
                
                if (!is_dir($dir)) {
                    if (!@mkdir($dir, 0755, true)) {
                        $error = error_get_last();
                        \Log::error('Error al crear directorio', [
                            'dir' => $dir,
                            'error' => $error ? $error['message'] : 'Error desconocido'
                        ]);
                        throw new \Exception('No se pudo crear el directorio de imágenes');
                    }
                }
                
                if (!is_writable($dir)) {
                    $perms = substr(sprintf('%o', fileperms($dir)), -4);
                    \Log::error('Permisos de directorio insuficientes', [
                        'dir' => $dir,
                        'perms' => $perms,
                        'owner' => posix_getpwuid(fileowner($dir))['name'] ?? 'desconocido',
                        'group' => posix_getgrgid(filegroup($dir))['name'] ?? 'desconocido'
                    ]);
                    throw new \Exception('El directorio no tiene permisos de escritura');
                }
                
                if ($product->image) {
                    $oldImagePath = public_path(parse_url($product->image, PHP_URL_PATH));
                    if (file_exists($oldImagePath) && is_file($oldImagePath)) {
                        if (!@unlink($oldImagePath)) {
                            throw new \Exception('No se pudo eliminar la imagen anterior');
                        }
                    }
                }

                $file = $request->file('image');
                $imageName = Str::slug($validated['name']).'-'.uniqid().'.'.$file->getClientOriginalExtension();
                $destination = $dir . '/' . $imageName;
                
                if (!$file->move($dir, $imageName)) {
                    throw new \Exception('Error al mover el archivo subido');
                }
                
                if (!file_exists($destination)) {
                    throw new \Exception('El archivo no se subió correctamente');
                }
                
                $product->image = '/images/products/'.$imageName;
            } catch (\Exception $e) {
                \Log::error('Error al subir la imagen: ' . $e->getMessage());
                return back()->with('error', 'Error al subir la imagen: ' . $e->getMessage());
            }
        }

        $product->save();

        return back()->with('success', 'Producto actualizado correctamente');
    }

    public function products()
    {
        $this->checkAdmin();
        
        $products = Product::all()->map(function ($product) {
            $image = $product->image;
            $placeholder = url('/images/placeholder.jpg');
            if (!$image) {
                $image = $placeholder;
            } elseif (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            } elseif (str_starts_with($image, '/')) {
                $publicPath = public_path(ltrim($image, '/'));
                $image = file_exists($publicPath) ? url($image) : $placeholder;
            } elseif (str_starts_with($image, 'images/')) {
                $publicPath = public_path($image);
                $image = file_exists($publicPath) ? url('/' . $image) : $placeholder;
            } elseif (str_starts_with($image, 'public/')) {
                $publicPath = public_path(substr($image, 7));
                $image = file_exists($publicPath) ? url('/' . ltrim(substr($image, 7), '/')) : $placeholder;
            } elseif (str_contains($image, '/')) {
                $storagePublic = public_path('storage/' . ltrim($image, '/'));
                $image = file_exists($storagePublic) ? url('/storage/' . ltrim($image, '/')) : $placeholder;
            } else {
                $publicPath = public_path('images/products/' . ltrim($image, '/'));
                $image = file_exists($publicPath) ? url('/images/products/' . ltrim($image, '/')) : $placeholder;
            }

            $category = Category::where('name', $product->category)->first();
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'image_url' => $image,
                'category' => $category ? $category->name : null,
                'category_data' => $category ? [
                    'id' => $category->id,
                    'name' => $category->name,
                    'emoji' => $category->emoji,
                ] : null,
            ];
        });

        $categories = Category::all()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'emoji' => $category->emoji,
                'description' => $category->description,
                'value' => (string)$category->id,
                'label' => $category->emoji . ' ' . $category->name
            ];
        });

        return Inertia::render('Admin/Products', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $this->checkAdmin();
    
        $categories = \App\Models\Category::all();
    
        return Inertia::render('Admin/Products/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        ini_set('upload_max_filesize', '10M');
        ini_set('post_max_size', '10M');
        ini_set('max_execution_time', '300');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:10240',
        ]);

        $product = new Product();
        $product->name = $validated['name'];
        $product->description = $validated['description'] ?? null;
        $product->price = $validated['price'];
        $product->stock = $validated['stock'];
        $product->category = $validated['category'];

        if ($request->hasFile('image')) {
            try {
                $dir = public_path('images/products');
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }
                $file = $request->file('image');
                $imageName = Str::slug($validated['name']).'-'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move($dir, $imageName);
                $product->image = $imageName;
            } catch (\Throwable $e) {
                return back()->with('error', 'No se pudo subir la imagen: '.$e->getMessage());
            }
        }

        $product->save();

        return redirect()->route('admin.products')
            ->with('success', 'Producto creado exitosamente');
    }

    public function destroy(Product $product)
    {
        $this->checkAdmin();
        
        // Guardar datos del producto en sesión antes de eliminar
        $deletedProduct = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'image' => $product->image,
            'category' => $product->category,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at
        ];
        
        // Guardar en sesión para posible restauración
        session(['last_deleted_product' => $deletedProduct]);
        session(['last_deleted_time' => now()]);
        
        // Eliminar permanentemente
        $product->delete();
        
        return redirect()->route('admin.products')->with('success', 'Producto eliminado');
    }

    public function bulkDelete(Request $request)
    {
        $this->checkAdmin();
    
        $productIds = $request->input('product_ids', []);
        
        \Log::info('Bulk delete request received', ['product_ids' => $productIds]);
    
        if (is_string($productIds)) {
            $productIds = json_decode($productIds, true) ?? [];
        }
    
        if (!is_array($productIds)) {
            $productIds = [$productIds];
        }
    
        $productIds = array_filter($productIds, function($id) {
            return is_numeric($id) && $id > 0;
        });
        $productIds = array_map('intval', $productIds);
        $productIds = array_unique($productIds);
    
        \Log::info('Processed product IDs', ['processed_ids' => $productIds]);
    
        if (empty($productIds)) {
            return redirect()->route('admin.products')->with('error', 'No se seleccionaron productos válidos');
        }
    
        try {
            $productsToDelete = Product::whereIn('id', $productIds)->get();
            $existingIds = $productsToDelete->pluck('id')->toArray();
        
            \Log::info('Products found for deletion', ['existing_ids' => $existingIds]);
        
            if (empty($existingIds)) {
                return redirect()->route('admin.products')->with('error', 'No se encontraron los productos seleccionados');
            }
    
            // Guardar datos para posible restauración
            $deletedProducts = $productsToDelete->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'image' => $product->image,
                    'category' => $product->category,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at
                ];
            })->toArray();
    
            session(['last_deleted_products' => $deletedProducts]);
            session(['last_deleted_time' => now()]);
            session(['last_deletion_type' => 'bulk']);
    
            // Eliminar permanentemente
            $deletedCount = Product::whereIn('id', $existingIds)->delete();
        
            \Log::info('Products deleted successfully', ['count' => $deletedCount]);
    
            $message = $deletedCount === 1 
                ? '1 producto eliminado exitosamente' 
                : "{$deletedCount} productos eliminados exitosamente";
                
            return redirect()->route('admin.products')->with('success', $message);
    
        } catch (\Exception $e) {
            \Log::error('Error in bulk delete: ' . $e->getMessage());
            return redirect()->route('admin.products')->with('error', 'Error al eliminar los productos seleccionados: ' . $e->getMessage());
        }
    }

    public function deleteAll()
    {
        $this->checkAdmin();

        try {
            $products = Product::all();
            $count = $products->count();

            if ($count > 0) {
                Product::query()->delete();
                return redirect()->route('admin.products')->with('success', "Todos los productos ({$count}) eliminados exitosamente");
            } else {
                return redirect()->route('admin.products')->with('info', 'No hay productos para eliminar');
            }

        } catch (\Exception $e) {
            return redirect()->route('admin.products')->with('error', 'Error al eliminar todos los productos');
        }
    }

    public function restore(Request $request)
    {
        $this->checkAdmin();
    
        try {
            // Verificar si hay productos eliminados recientemente en la sesión
            $deletionType = session('last_deletion_type', 'single');
            $deletedTime = session('last_deleted_time');
            
            // Verificar que no hayan pasado más de 5 minutos
            if (!$deletedTime || now()->diffInMinutes($deletedTime) > 5) {
                return redirect()->route('admin.products')->with('error', 'El tiempo para deshacer ha expirado (máximo 5 minutos)');
            }
    
            $restoredCount = 0;
    
            if ($deletionType === 'bulk') {
                // Restaurar múltiples productos
                $deletedProducts = session('last_deleted_products', []);
                
                foreach ($deletedProducts as $productData) {
                    // Crear nuevo producto con los datos guardados
                    $product = new Product();
                    $product->name = $productData['name'];
                    $product->description = $productData['description'];
                    $product->price = $productData['price'];
                    $product->stock = $productData['stock'];
                    $product->image = $productData['image'];
                    $product->category = $productData['category'];
                    $product->created_at = $productData['created_at'];
                    $product->updated_at = $productData['updated_at'];
                    $product->save();
                    
                    $restoredCount++;
                }
                
                // Limpiar sesión
                session()->forget(['last_deleted_products', 'last_deleted_time', 'last_deletion_type']);
                
            } else {
                // Restaurar producto individual
                $deletedProduct = session('last_deleted_product');
                
                if ($deletedProduct) {
                    $product = new Product();
                    $product->name = $deletedProduct['name'];
                    $product->description = $deletedProduct['description'];
                    $product->price = $deletedProduct['price'];
                    $product->stock = $deletedProduct['stock'];
                    $product->image = $deletedProduct['image'];
                    $product->category = $deletedProduct['category'];
                    $product->created_at = $deletedProduct['created_at'];
                    $product->updated_at = $deletedProduct['updated_at'];
                    $product->save();
                    
                    $restoredCount++;
                    
                    // Limpiar sesión
                    session()->forget(['last_deleted_product', 'last_deleted_time', 'last_deletion_type']);
                }
            }
    
            if ($restoredCount > 0) {
                $message = $restoredCount === 1 
                    ? '1 producto restaurado exitosamente' 
                    : "{$restoredCount} productos restaurados exitosamente";
                    
                return redirect()->route('admin.products')->with('success', $message);
            } else {
                return redirect()->route('admin.products')->with('error', 'No hay productos para restaurar');
            }
    
        } catch (\Exception $e) {
            \Log::error('Error restoring products: ' . $e->getMessage());
            return redirect()->route('admin.products')->with('error', 'Error al restaurar los productos: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar gestión de promociones - CORREGIDO
     */
    public function promotions()
    {
        $this->checkAdmin();
        
        // Obtener productos con promociones activas usando el scope corregido
        $productsWithPromotions = Product::with(['activePromotion', 'categoryRelation'])
            ->whereHas('promotions', function($query) {
                $query->active();
            })
            ->get()
            ->map(function($product) {
                return $this->formatProductWithPromotion($product);
            });

        $productsWithoutPromotions = Product::with(['categoryRelation'])
            ->whereDoesntHave('promotions', function($query) {
                $query->active();
            })
            ->get()
            ->map(function($product) {
                return $this->formatProduct($product);
            });

        // Obtener todas las promociones para el historial - CORREGIDO
        $allPromotions = Promotion::with('product.categoryRelation')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($promotion) {
                return [
                    'id' => $promotion->id,
                    'product_id' => $promotion->product_id,
                    'product_name' => $promotion->product->name,
                    'category_name' => $promotion->product->categoryRelation->name ?? 'Sin categoría',
                    'discount_percentage' => $promotion->discount_percentage,
                    'original_price' => $promotion->original_price,
                    'promotional_price' => $promotion->calculatePromotionalPrice(),
                    'start_date' => $promotion->start_date?->format('Y-m-d H:i:s'),
                    'end_date' => $promotion->end_date?->format('Y-m-d H:i:s'),
                    'is_active' => $promotion->is_active,
                    'is_currently_active' => $promotion->is_currently_active, // Usar el accessor corregido
                    'created_at' => $promotion->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return Inertia::render('Admin/Promotions', [
            'productsWithPromotions' => $productsWithPromotions,
            'productsWithoutPromotions' => $productsWithoutPromotions,
            'allPromotions' => $allPromotions,
            'discountOptions' => [
                ['value' => 10, 'label' => '10% OFF'],
                ['value' => 15, 'label' => '15% OFF'],
                ['value' => 20, 'label' => '20% OFF'],
                ['value' => 25, 'label' => '25% OFF'],
                ['value' => 30, 'label' => '30% OFF'],
                ['value' => 40, 'label' => '40% OFF'],
                ['value' => 50, 'label' => '50% OFF'],
            ]
        ]);
    }

    private function formatProductWithPromotion($product)
    {
        $baseProduct = $this->formatProduct($product);
        $activePromotion = $product->activePromotion;

        return array_merge($baseProduct, [
            'has_active_promotion' => true,
            'promotion_data' => $activePromotion ? [
                'id' => $activePromotion->id,
                'discount_percentage' => $activePromotion->discount_percentage,
                'original_price' => $activePromotion->original_price,
                'promotional_price' => $activePromotion->calculatePromotionalPrice(),
                'start_date' => $activePromotion->start_date?->format('Y-m-d H:i:s'),
                'end_date' => $activePromotion->end_date?->format('Y-m-d H:i:s'),
                'is_active' => $activePromotion->is_active,
                'is_currently_active' => $activePromotion->is_currently_active,
                'savings_amount' => $activePromotion->original_price - $activePromotion->calculatePromotionalPrice(),
            ] : null,
            'promotional_price' => $product->promotional_price,
            'discount_percentage' => $product->discount_percentage,
            'savings_amount' => $product->savings_amount,
        ]);
    }

    private function formatProduct($product)
    {
        $image = $product->image;
        $placeholder = url('/images/placeholder.jpg');
    
        if (!$image) {
            $image = $placeholder;
        } elseif (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
        } elseif (str_starts_with($image, '/')) {
            $publicPath = public_path(ltrim($image, '/'));
            $image = file_exists($publicPath) ? url($image) : $placeholder;
        } elseif (str_starts_with($image, 'images/')) {
            $publicPath = public_path($image);
            $image = file_exists($publicPath) ? url('/' . $image) : $placeholder;
        } else {
            $publicPath = public_path('images/products/' . ltrim($image, '/'));
            $image = file_exists($publicPath) ? url('/images/products/' . ltrim($image, '/')) : $placeholder;
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'image_url' => $image,
            'category_name' => $product->categoryRelation->name ?? 'Sin categoría',
            'category_emoji' => $product->categoryRelation->emoji ?? '📦',
            'has_active_promotion' => $product->has_active_promotion,
        ];
    }

    public function addPromotion(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:1|max:90',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        try {
            $product = Product::findOrFail($validated['product_id']);

            // Desactivar cualquier promoción activa existente
            Promotion::where('product_id', $product->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);

            // Crear nueva promoción
            $promotion = new Promotion();
            $promotion->product_id = $product->id;
            $promotion->discount_percentage = $validated['discount_percentage'];
            $promotion->original_price = $product->price;
            $promotion->start_date = $validated['start_date'] ? 
                Carbon::parse($validated['start_date'])->setTimezone('America/Asuncion') : null;
            $promotion->end_date = $validated['end_date'] ? 
                Carbon::parse($validated['end_date'])->setTimezone('America/Asuncion') : null;
            $promotion->is_active = true;
            $promotion->save();

            return response()->json([
                'success' => true,
                'message' => 'Promoción agregada exitosamente',
                'promotion' => [
                    'id' => $promotion->id,
                    'discount_percentage' => $promotion->discount_percentage,
                    'promotional_price' => $promotion->calculatePromotionalPrice(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar promoción: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePromotion(Request $request, Promotion $promotion)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'discount_percentage' => 'required|numeric|min:1|max:90',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        try {
            // Convertir fechas a la zona horaria correcta
            if (isset($validated['start_date'])) {
                $validated['start_date'] = $validated['start_date'] ? Carbon::parse($validated['start_date'])->setTimezone('America/Asuncion') : null;
            }
            if (isset($validated['end_date'])) {
                $validated['end_date'] = $validated['end_date'] ? Carbon::parse($validated['end_date'])->setTimezone('America/Asuncion') : null;
             }

            $promotion->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Promoción actualizada exitosamente',
                'promotion' => [
                    'id' => $promotion->id,
                    'discount_percentage' => $promotion->discount_percentage,
                    'promotional_price' => $promotion->calculatePromotionalPrice(),
                    'is_active' => $promotion->is_active,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar promoción: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removePromotion(Promotion $promotion)
    {
        $this->checkAdmin();

        try {
            $promotion->delete();

            return response()->json([
                'success' => true,
                'message' => 'Promoción eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar promoción: ' . $e->getMessage()
            ], 500);
        }
    }
}