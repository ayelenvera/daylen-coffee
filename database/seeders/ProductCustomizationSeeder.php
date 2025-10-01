<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductTopping;
use App\Models\ProductAddon;

class ProductCustomizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Actualizar productos existentes con categorías y opciones de personalización
        $products = Product::all();
        
        foreach ($products as $product) {
            $productName = strtolower($product->name);
            
            // Asignar categorías basadas en el nombre del producto
            if (str_contains($productName, 'café') || str_contains($productName, 'espresso') || 
                str_contains($productName, 'americano') || str_contains($productName, 'capuchino') || 
                str_contains($productName, 'latte') || str_contains($productName, 'mocha') || 
                str_contains($productName, 'macchiato') || str_contains($productName, 'cortado')) {
                $product->category = 'bebidas_calientes';
                $product->has_size = true;
                $product->has_toppings = true;
                $product->has_addons = true;
            } elseif (str_contains($productName, 'iced') || str_contains($productName, 'frappé') || 
                      str_contains($productName, 'cold brew')) {
                $product->category = 'bebidas_frias';
                $product->has_size = true;
                $product->has_toppings = true;
                $product->has_addons = true;
            } elseif (str_contains($productName, 'té') || str_contains($productName, 'manzanilla') || 
                      str_contains($productName, 'menta')) {
                $product->category = 'tes_infusiones';
                $product->has_size = true;
                $product->has_addons = true;
            } elseif (str_contains($productName, 'chocolate') || str_contains($productName, 'submarino')) {
                $product->category = 'chocolates';
                $product->has_size = true;
                $product->has_toppings = true;
            } elseif (str_contains($productName, 'croissant') || str_contains($productName, 'medialuna') || 
                      str_contains($productName, 'factura') || str_contains($productName, 'cañoncito') || 
                      str_contains($productName, 'vigilante') || str_contains($productName, 'alfajor')) {
                $product->category = 'pasteleria';
                $product->has_addons = false;
            } elseif (str_contains($productName, 'torta') || str_contains($productName, 'cheesecake') || 
                      str_contains($productName, 'brownie') || str_contains($productName, 'lemon pie')) {
                $product->category = 'tortas_postres';
                $product->has_addons = false;
            } elseif (str_contains($productName, 'sandwich') || str_contains($productName, 'tostado') || 
                      str_contains($productName, 'empanada')) {
                $product->category = 'salados';
                $product->has_addons = true;
            } else {
                $product->category = 'otros';
            }
            
            $product->save();
        }

        // Crear tamaños para productos de bebidas
        $beverageProducts = Product::whereIn('category', ['bebidas_calientes', 'bebidas_frias', 'tes_infusiones', 'chocolates'])->get();
        
        foreach ($beverageProducts as $product) {
            // Tamaños estándar
            ProductSize::create([
                'product_id' => $product->id,
                'size' => 'Pequeño',
                'price' => 0
            ]);
            
            ProductSize::create([
                'product_id' => $product->id,
                'size' => 'Mediano',
                'price' => 2000
            ]);
            
            ProductSize::create([
                'product_id' => $product->id,
                'size' => 'Grande',
                'price' => 4000
            ]);
        }

        // Crear coberturas para productos que las tienen
        $toppingProducts = Product::where('has_toppings', true)->get();
        
        foreach ($toppingProducts as $product) {
            ProductTopping::create([
                'product_id' => $product->id,
                'name' => 'Chocolate',
                'price' => 1500
            ]);
            
            ProductTopping::create([
                'product_id' => $product->id,
                'name' => 'Vainilla',
                'price' => 1500
            ]);
            
            ProductTopping::create([
                'product_id' => $product->id,
                'name' => 'Caramelo',
                'price' => 2000
            ]);
            
            ProductTopping::create([
                'product_id' => $product->id,
                'name' => 'Canela',
                'price' => 1000
            ]);
        }

        // Crear agregados para productos que los tienen
        $addonProducts = Product::where('has_addons', true)->get();
        
        foreach ($addonProducts as $product) {
            ProductAddon::create([
                'product_id' => $product->id,
                'name' => 'Leche Extra',
                'price' => 1000,
                'has_quantity' => true
            ]);
            
            ProductAddon::create([
                'product_id' => $product->id,
                'name' => 'Azúcar Extra',
                'price' => 500,
                'has_quantity' => true
            ]);
            
            ProductAddon::create([
                'product_id' => $product->id,
                'name' => 'Crema Batida',
                'price' => 2000,
                'has_quantity' => false
            ]);
            
            ProductAddon::create([
                'product_id' => $product->id,
                'name' => 'Hielo Extra',
                'price' => 500,
                'has_quantity' => false
            ]);
        }

        $this->command->info('✅ Personalizaciones de productos creadas exitosamente!');
        $this->command->info('📊 Categorías asignadas a productos');
        $this->command->info('📏 Tamaños creados para bebidas');
        $this->command->info('🍫 Coberturas creadas para productos elegibles');
        $this->command->info('➕ Agregados creados para productos elegibles');
    }
}
