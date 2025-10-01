<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Obtener parámetros de la URL
        $category = $request->query('category');
        $search = $request->query('search', '');
        $sort = $request->query('sort', 'name');
        
        // Debug: Mostrar parámetros recibidos
        \Log::info('Parámetros de búsqueda:', [
            'category' => $category,
            'search' => $search,
            'sort' => $sort
        ]);
        
        // Cargar productos con su categoría y promociones
        $query = Product::with(['categoryRelation' => function($q) {
                $q->select('id', 'name', 'emoji');
            }, 'promotions' => function($q) {
                $q->active();
            }])
            ->where('stock', '>', 0);
        
        // Aplicar filtros
        if ($category && is_numeric($category)) {
            $query->where('category', (int)$category);
        }
        
        // Búsqueda mejorada (nombre o descripción)
        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }
        
        // Ordenamiento mejorado
        $orderField = 'name';
        $orderDirection = 'asc';
        
        switch ($sort) {
            case 'name_desc':
                $orderField = 'name';
                $orderDirection = 'desc';
                break;
            case 'price':
                $orderField = 'price';
                $orderDirection = 'desc';
                break;
            case 'price_desc':
                $orderField = 'price';
                $orderDirection = 'asc';
                break;
            case 'stock':
                $orderField = 'stock';
                $orderDirection = 'desc';
                break;
        }
        
        $query->orderBy($orderField, $orderDirection);
        
        // Obtener productos
        $products = $query->get()->map(function ($product) {
            // Normalizar y validar ruta de imagen
            $raw = $product->image;
            $placeholder = url('/images/placeholder.jpg');

            if (!$raw) {
                $image = $placeholder;
            } elseif (Str::startsWith($raw, ['http://', 'https://'])) {
                $image = $raw;
            } elseif (Str::startsWith($raw, ['/'])) {
                $candidate = public_path($raw);
                $image = file_exists($candidate) ? url($raw) : $placeholder;
            } elseif (Str::startsWith($raw, ['images/'])) {
                $candidate = public_path('/'.ltrim($raw, '/'));
                $image = file_exists($candidate) ? url('/'.ltrim($raw, '/')) : $placeholder;
            } else {
                $rel = '/images/products/'.ltrim($raw, '/');
                $candidate = public_path($rel);
                $image = file_exists($candidate) ? url($rel) : $placeholder;
            }

            // Obtener la categoría completa
            $category = $product->categoryRelation;
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'image_url' => $image,
                'category_relation' => $product->categoryRelation ? [
                    'id' => $product->categoryRelation->id,
                    'name' => $product->categoryRelation->name,
                    'emoji' => [
                        '0' => '☕',
                        '1' => '🍵',
                        '2' => '🍰',
                        '3' => '🥐',
                        '4' => '🥗',
                        '5' => '🧃',
                        '6' => '🍫',
                        '7' => '🥖',
                        '10' => '🧃',
                    ][$product->categoryRelation->emoji] ?? '📦',
                    'emoji_code' => $product->categoryRelation->emoji
                ] : [
                    'id' => null,
                    'name' => 'Sin categoría',
                    'emoji' => '📦',
                    'emoji_code' => null
                ],
                'category_name' => $product->categoryRelation ? $product->categoryRelation->name : 'Sin categoría',
                'category_emoji' => $product->categoryRelation ? (
                    [
                        '0' => '☕',
                        '1' => '🍵',
                        '2' => '🍰',
                        '3' => '🥐',
                        '4' => '🥗',
                        '5' => '🧃',
                        '6' => '🍫',
                        '7' => '🥖',
                        '10' => '🧃',
                    ][$product->categoryRelation->emoji] ?? '📦'
                ) : '📦',
                // Datos de promoción
                'has_active_promotion' => $product->has_active_promotion,
                'promotional_price' => $product->promotional_price,
                'discount_percentage' => $product->discount_percentage,
                'savings_amount' => $product->savings_amount,
            ];
        });

        // Obtener productos con promociones activas para la sección especial
        $promotionalProducts = Product::with(['categoryRelation' => function($q) {
                $q->select('id', 'name', 'emoji');
            }, 'promotions' => function($q) {
                $q->active();
            }])
            ->whereHas('promotions', function($query) {
                $query->active();
            })
            ->where('stock', '>', 0)
            ->get()
            ->map(function ($product) {
                // Normalizar y validar ruta de imagen
                $raw = $product->image;
                $placeholder = url('/images/placeholder.jpg');

                if (!$raw) {
                    $image = $placeholder;
                } elseif (Str::startsWith($raw, ['http://', 'https://'])) {
                    $image = $raw;
                } elseif (Str::startsWith($raw, ['/'])) {
                    $candidate = public_path($raw);
                    $image = file_exists($candidate) ? url($raw) : $placeholder;
                } elseif (Str::startsWith($raw, ['images/'])) {
                    $candidate = public_path('/'.ltrim($raw, '/'));
                    $image = file_exists($candidate) ? url('/'.ltrim($raw, '/')) : $placeholder;
                } else {
                    $rel = '/images/products/'.ltrim($raw, '/');
                    $candidate = public_path($rel);
                    $image = file_exists($candidate) ? url($rel) : $placeholder;
                }

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'image_url' => $image,
                    'category_relation' => $product->categoryRelation ? [
                        'id' => $product->categoryRelation->id,
                        'name' => $product->categoryRelation->name,
                        'emoji' => [
                            '0' => '☕',
                            '1' => '🍵',
                            '2' => '🍰',
                            '3' => '🥐',
                            '4' => '🥗',
                            '5' => '🧃',
                            '6' => '🍫',
                            '7' => '🥖',
                            '10' => '🧃',
                        ][$product->categoryRelation->emoji] ?? '📦',
                        'emoji_code' => $product->categoryRelation->emoji
                    ] : [
                        'id' => null,
                        'name' => 'Sin categoría',
                        'emoji' => '📦',
                        'emoji_code' => null
                    ],
                    'category_name' => $product->categoryRelation ? $product->categoryRelation->name : 'Sin categoría',
                    'category_emoji' => $product->categoryRelation ? (
                        [
                            '0' => '☕',
                            '1' => '🍵',
                            '2' => '🍰',
                            '3' => '🥐',
                            '4' => '🥗',
                            '5' => '🧃',
                            '6' => '🍫',
                            '7' => '🥖',
                            '10' => '🧃',
                        ][$product->categoryRelation->emoji] ?? '📦'
                    ) : '📦',
                    // Datos de promoción
                    'has_active_promotion' => $product->has_active_promotion,
                    'promotional_price' => $product->promotional_price,
                    'discount_percentage' => $product->discount_percentage,
                    'savings_amount' => $product->savings_amount,
                ];
            });

        // Orden personalizado para las categorías
        $categoryOrder = [
            'Café', 'Té', 'Desayunos', 'Postres', 
            'Ensaladas', 'Bebidas', 'Snacks', 'Panadería'
        ];
        
        // Mapa de códigos de emoji a emojis reales
        $emojiMap = [
            '0' => '☕',
            '1' => '🍵',
            '2' => '🍰',
            '3' => '🥐',
            '4' => '🥗',
            '5' => '🧃',
            '6' => '🍫',
            '7' => '🥖',
            '10' => '🧃',
        ];
        
        // Obtener todas las categorías para los filtros
        $categories = Category::select('id', 'name', 'emoji')
            ->where('is_active', true)
            ->get()
            ->map(function($category) use ($emojiMap) {
                // Convertir el número de emoji a emoji real
                $emoji = $emojiMap[$category->emoji] ?? '📦';
                
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'emoji' => $emoji,
                    'emoji_code' => $category->emoji
                ];
            })
            ->sortBy(function($category) use ($categoryOrder) {
                $index = array_search($category['name'], $categoryOrder);
                return $index === false ? 999 : $index;
            })
            ->values();
        
        // Obtener pedidos recientes si el usuario está autenticado
        $recentOrders = [];
        if (auth()->check() && !auth()->user()->is_admin) {
            $recentOrders = auth()->user()->orders()
                ->with('orderItems.product')
                ->orderBy('created_at', 'desc')
                ->limit(2)
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'total' => $order->total,
                        'status' => $order->status,
                        'created_at' => $order->created_at,
                        'orderItems' => $order->orderItems->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'quantity' => $item->quantity,
                                'product' => $item->product ? [
                                    'id' => $item->product->id,
                                    'name' => $item->product->name
                                ] : null
                            ];
                        })
                    ];
                })->toArray();
        }
        
        // Obtener conteo del carrito CORREGIDO
        $cartData = \App\Services\CartService::getCartData();
        $cartCount = $cartData['count'] ?? 0;

        // Debug para verificar
        \Log::info('Cart count in HomeController:', [
            'count' => $cartCount,
            'session_id' => session()->getId(),
            'cart_items' => $cartData['items'] ?? []
        
        ]);  
      
        return Inertia::render('Home', [
            'products' => $products,
            'promotionalProducts' => $promotionalProducts,
            'categories' => $categories,
            'recentOrders' => $recentOrders,
            'cartCount' => $cartCount, // Asegurarse de pasar el cartCount
            'currentCategory' => $category ? (int)$category : null,
            'currentSearch' => $search,
            'currentSort' => $sort,
            'filters' => [
                'search' => $search,
                'sort' => $sort,
                'category' => $category
            ]
        ]);
    }
}