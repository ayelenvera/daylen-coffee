<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Desactivar restricciones de clave foránea temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Limpiar tablas
        $tables = ['products', 'categories', 'users'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        // Ejecutar seeders en el orden correcto
        $this->call([
            // Primero las categorías (dependencia principal)
            CategoriesTableSeeder::class,
            
            // Luego los usuarios (para tener creadores de productos)
            UserSeeder::class,
            
            // Luego los productos (dependen de categorías y usuarios)
            ProductSeeder::class,
            
            // Luego las personalizaciones (dependen de productos)
            ProductCustomizationSeeder::class,
        ]);

        // Reactivar restricciones de clave foránea
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        echo "✅ ¡Base de datos sembrada exitosamente!\n";
    }
}
