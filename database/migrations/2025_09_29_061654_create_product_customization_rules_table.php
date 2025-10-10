
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_customization_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->json('enabled_options'); // ['quantity', 'size', 'sugar', 'toppings', 'addons']
            $table->json('size_options')->nullable(); // ['Pequeño', 'Mediano', 'Grande']
            $table->json('sugar_options')->nullable(); // ['Sin azúcar', 'Poca', 'Normal', 'Extra']
            $table->json('excluded_products')->nullable(); // Para reglas como "excepto agua"
            $table->timestamps();
        });
    }
};