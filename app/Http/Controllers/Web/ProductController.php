<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Mostrar la lista de productos
     */
    public function index()
    {
        $products = Product::latest()
            ->select('id', 'name', 'description', 'price', 'image', 'stock')
            ->get();

        return Inertia::render('Products/Index', [
            'products' => $products
        ]);
    }

    /**
     * Mostrar el formulario de creación de producto
     */
    public function create()
    {
        return Inertia::render('Products/Create');
    }

    /**
     * Almacenar un nuevo producto
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string|url',
        ]);

        $product = new Product($validated);
        $product->image = $validated['image'] ?? '/images/placeholder.jpg';
        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Producto creado exitosamente');
    }

    /**
     * Mostrar los detalles de un producto
     */
    public function show(Product $product)
    {
        return Inertia::render('Products/Show', [
            'product' => $product->only('id', 'name', 'description', 'price', 'image', 'stock')
        ]);
    }

    /**
     * Mostrar el formulario de edición de producto
     */
    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', [
            'product' => $product
        ]);
    }

    /**
     * Actualizar un producto existente
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string|url',
        ]);

        $product->fill($validated);
        $product->image = $validated['image'] ?? $product->image;
        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Eliminar un producto
     */
    public function destroy(Product $product)
    {
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Producto eliminado exitosamente');
    }
}
