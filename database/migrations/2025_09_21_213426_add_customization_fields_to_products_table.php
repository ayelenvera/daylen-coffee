<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->default('bebidas');
            $table->boolean('has_size')->default(false);
            $table->boolean('has_toppings')->default(false);
            $table->boolean('has_addons')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['category', 'has_size', 'has_toppings', 'has_addons']);
        });
    }
};
