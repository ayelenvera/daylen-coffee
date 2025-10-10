<?php
// app/Http/Controllers/Admin/CustomizationRulesController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCustomizationRule;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomizationRulesController extends Controller
{
    /**
     * Verificar si el usuario es administrador
     */
    protected function checkAdmin()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Acceso no autorizado');
        }
    }

    /**
     * Mostrar lista de reglas de personalización
     */
    public function index()
    {
        $this->checkAdmin();

        $rules = ProductCustomizationRule::with('category')->get();
        $categories = Category::active()->get();
        $products = Product::with('categoryRelation')->get();

        return Inertia::render('Admin/CustomizationRules', [
            'rules' => $rules->map(function($rule) {
                return [
                    'id' => $rule->id,
                    'category_id' => $rule->category_id,
                    'category_name' => $rule->category->name,
                    'category_emoji' => $rule->category->emoji,
                    'enabled_options' => $rule->enabled_options ?? [],
                    'size_options' => $rule->size_options ?? [],
                    'default_size' => $rule->default_size,
                    'sugar_options' => $rule->sugar_options ?? [],
                    'topping_options' => $rule->topping_options ?? [],
                    'addon_options' => $rule->addon_options ?? [],
                    'excluded_products' => $rule->excluded_products ?? [],
                    'created_at' => $rule->created_at,
                    'updated_at' => $rule->updated_at,
                ];
            }),
            'categories' => $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'emoji' => $category->emoji,
                ];
            }),
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category,
                    'category_name' => $product->categoryRelation->name ?? 'Sin categoría',
                ];
            }),
            'available_options' => [
                'quantity' => 'Cantidad',
                'size' => 'Tamaño', 
                'sugar' => 'Azúcar',
                'toppings' => 'Coberturas',
                'addons' => 'Agregados'
            ],
            'default_size_options' => [
                ['name' => 'Pequeño', 'price' => 0],
                ['name' => 'Mediano', 'price' => 2000],
                ['name' => 'Grande', 'price' => 4000]
            ],
            'default_sugar_options' => ['Sin azúcar', 'Poca', 'Normal', 'Extra']
        ]);
    }

    /**
     * Crear o actualizar regla de personalización
     */
    public function store(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'enabled_options' => 'required|array',
            'enabled_options.*' => 'string|in:quantity,size,sugar,toppings,addons',
            'size_options' => 'nullable|array',
            'default_size' => 'nullable|string',
            'sugar_options' => 'nullable|array',
            'topping_options' => 'nullable|array',
            'addon_options' => 'nullable|array',
            'excluded_products' => 'nullable|array',
            'excluded_products.*' => 'exists:products,id'
        ]);

        try {
            // Buscar regla existente o crear nueva
            $rule = ProductCustomizationRule::updateOrCreate(
                ['category_id' => $validated['category_id']],
                [
                    'enabled_options' => $validated['enabled_options'],
                    'size_options' => $validated['size_options'] ?? [],
                    'default_size' => $validated['default_size'] ?? null,
                    'sugar_options' => $validated['sugar_options'] ?? [],
                    'topping_options' => $validated['topping_options'] ?? [],
                    'addon_options' => $validated['addon_options'] ?? [],
                    'excluded_products' => $validated['excluded_products'] ?? [],
                ]
            );

            // Redirigir de vuelta con mensaje de éxito
            return redirect()->route('admin.customization-rules')
                ->with('success', 'Regla de personalización guardada correctamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al guardar la regla: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar regla de personalización
     */
    public function update(Request $request, ProductCustomizationRule $customizationRule)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'enabled_options' => 'required|array',
            'enabled_options.*' => 'string|in:quantity,size,sugar,toppings,addons',
            'size_options' => 'nullable|array',
            'default_size' => 'nullable|string',
            'sugar_options' => 'nullable|array',
            'topping_options' => 'nullable|array',
            'addon_options' => 'nullable|array',
            'excluded_products' => 'nullable|array',
            'excluded_products.*' => 'exists:products,id'
        ]);

        try {
            $customizationRule->update([
                'enabled_options' => $validated['enabled_options'],
                'size_options' => $validated['size_options'] ?? [],
                'default_size' => $validated['default_size'] ?? null,
                'sugar_options' => $validated['sugar_options'] ?? [],
                'topping_options' => $validated['topping_options'] ?? [],
                'addon_options' => $validated['addon_options'] ?? [],
                'excluded_products' => $validated['excluded_products'] ?? [],
            ]);

            return redirect()->route('admin.customization-rules')
                ->with('success', 'Regla de personalización actualizada correctamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la regla: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar regla de personalización
     */
    public function destroy(ProductCustomizationRule $customizationRule)
    {
        $this->checkAdmin();

        try {
            $customizationRule->delete();

            return redirect()->route('admin.customization-rules')
                ->with('success', 'Regla de personalización eliminada correctamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar la regla: ' . $e->getMessage());
        }
    }
}