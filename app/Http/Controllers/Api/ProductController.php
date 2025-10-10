<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            // Cache por 1 hora (3600 segundos)
            $products = Cache::remember('products.all', 3600, function () {
                return Product::all();
            });

            return response()->json([
                'success' => true,
                'data' => $products,
                'cached' => Cache::has('products.all')
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener productos'
            ], 500);
        }
    }

    public function create()
    {
        return response()->json([
            'message' => 'No se puede crear un producto a través de la API'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Manejar la imagen
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = Storage::url($imagePath);
        } else {
            $validated['image'] = '/images/placeholder.jpg';
        }

        Product::create($validated);

        return response()->json([
            'message' => 'Producto creado correctamente'
        ]);
    }

    public function edit(Product $product)
    {
        return response()->json([
            'product' => $product
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096'
        ]);

        // Manejar la imagen
        if ($request->hasFile('image')) {
            try {
                $dir = public_path('images/products');
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }
                
                // Eliminar imagen anterior si existe
                if ($product->image) {
                    $oldImagePath = public_path(parse_url($product->image, PHP_URL_PATH));
                    if (file_exists($oldImagePath) && is_file($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }

                $file = $request->file('image');
                $imageName = Str::slug($validated['name']).'-'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move($dir, $imageName);
                $validated['image'] = '/images/products/'.$imageName;
            } catch (\Exception $e) {
                \Log::error('Error al subir la imagen: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error al subir la imagen: ' . $e->getMessage()
                ], 500);
            }
        }

        // Si se envía category_id, actualizamos la relación
        if (isset($validated['category'])) {
            $category = \App\Models\Category::find($validated['category']);
            if ($category) {
                $validated['category'] = $category->id;
            } else {
                unset($validated['category']);
            }
        }

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado correctamente',
            'data' => $product->fresh()
        ]);
    }

    public function destroy(Product $product)
    {
        // Eliminar imagen asociada
        if ($product->image && $product->image !== '/images/placeholder.jpg') {
            $imagePath = str_replace('/storage/', '', $product->image);
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado correctamente');
    }
}