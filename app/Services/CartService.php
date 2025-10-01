<?php
namespace App\Services;

use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\ProductCustomizationRule;
use App\Models\Category;

class CartService
{
    public static function getCartData()
    {
        $cart = Session::get('cart', [
            'items' => [],
            'total' => 0,
            'item_count' => 0,
        ]);

        $items = [];
        $total = 0;
        $count = 0;

        foreach ($cart['items'] as $productId => $item) {
            if (is_array($item) && isset($item['price'], $item['quantity'])) {
                $items[] = $item;
                $total += $item['price'] * $item['quantity'];
                $count += $item['quantity'];
            }
        }

        return [
            'items' => $items,
            'total' => $total,
            'count' => $count
        ];
    }

    public static function getProductCustomizationRules($productId)
    {
        $product = Product::with('categoryRelation')->find($productId);
    
        \Log::info("🔍 Buscando reglas para producto", [
            'product_id' => $productId,
            'product_name' => $product->name ?? 'No encontrado',
            'product_category' => $product->category ?? 'Sin categoría'
        ]);

        if (!$product || !$product->category) {
            return [
                'enabled_options' => ['quantity'],
                'size_options' => [],
                'sugar_options' => [],
                'topping_options' => [],
                'addon_options' => [],
                'default_size' => null
            ];
        }

        $rule = ProductCustomizationRule::where('category_id', $product->category)->first();

        \Log::info("📋 Resultado de búsqueda de regla", [
            'category_id_buscado' => $product->category,
            'regla_encontrada' => $rule ? 'SÍ' : 'NO'
        ]);
    
        if (!$rule) {
            return self::getDefaultRulesByCategory($product->categoryRelation->name ?? 'Sin categoría');
        }

        // ✅ CORREGIDO: EXCLUSIONES COMPLETAS PARA AGUA
        $enabledOptions = $rule->enabled_options;
        if ($rule->excluded_products && in_array($product->id, $rule->excluded_products)) {
            // Para productos excluidos (agua), quitar TODAS las opciones excepto cantidad
            $enabledOptions = array_diff($enabledOptions, ['size', 'sugar', 'toppings', 'addons']);
            \Log::info("🚫 Aplicando exclusiones completas para producto", [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'enabled_options_antes' => $rule->enabled_options,
                'enabled_options_despues' => $enabledOptions
            ]);
        }

        // ✅ CORREGIDO: Para Panadería, Postres, Snacks - NO tamaño por defecto con costo
        $categoryName = $product->categoryRelation->name ?? '';
        $shouldRemoveSizes = in_array($categoryName, ['Panadería', 'Postres', 'Snacks', 'Desayunos']);
        
        if ($shouldRemoveSizes) {
            $enabledOptions = array_diff($enabledOptions, ['size']);
            $sizeOptions = [];
            $defaultSize = null;
        } else {
            $sizeOptions = $rule->size_options ?? [];
            $defaultSize = $rule->default_size ?? null;
        }

        $result = [
            'enabled_options' => $enabledOptions,
            'size_options' => $sizeOptions,
            'default_size' => $defaultSize,
            'sugar_options' => $rule->sugar_options ?? [],
            'topping_options' => $rule->topping_options ?? [],
            'addon_options' => $rule->addon_options ?? []
        ];

        \Log::info("🎯 Reglas finales para producto", $result);

        return $result;
    }

    private static function getDefaultRulesByCategory($categoryName)
    {
        // ✅ CORREGIDO: Panadería, Postres, etc. NO tienen tamaño
        $sizeEnabled = in_array($categoryName, ['Café', 'Té', 'Bebidas', 'Ensaladas']);
        
        $enabledOptions = ['quantity'];
        if ($sizeEnabled) {
            $enabledOptions[] = 'size';
        }
        if (in_array($categoryName, ['Café', 'Té', 'Bebidas'])) {
            $enabledOptions[] = 'sugar';
        }

        return [
            'enabled_options' => $enabledOptions,
            'size_options' => $sizeEnabled ? [['name' => 'Mediano', 'price' => 0]] : [],
            'default_size' => $sizeEnabled ? 'Mediano' : null,
            'sugar_options' => ['Normal'],
            'topping_options' => [],
            'addon_options' => []
        ];
    }
}