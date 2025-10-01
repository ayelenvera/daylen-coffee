<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Café',
                'slug' => Str::slug('Café'),
                'emoji' => 0, // ☕
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Té',
                'slug' => Str::slug('Té'),
                'emoji' => 1, // 🍵
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Postres',
                'slug' => Str::slug('Postres'),
                'emoji' => 2, // 🍰
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desayunos',
                'slug' => Str::slug('Desayunos'),
                'emoji' => 3, // 🥐
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ensaladas',
                'slug' => Str::slug('Ensaladas'),
                'emoji' => 4, // 🥗
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bebidas',
                'slug' => Str::slug('Bebidas'),
                'emoji' => 10, // 🧃
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Snacks',
                'slug' => Str::slug('Snacks'),
                'emoji' => 6, // 🍫
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Panadería',
                'slug' => Str::slug('Panadería'),
                'emoji' => 7, // 🍩
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
