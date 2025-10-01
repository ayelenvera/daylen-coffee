<?php
// database/migrations/2025_XX_XX_XXXXXX_add_personalization_fields_to_orders.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Agregar campos a orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('user_id');
            $table->string('phone')->nullable()->after('customer_name');
            $table->text('address')->nullable()->after('phone');
            $table->string('payment_method')->default('efectivo')->after('address');
            $table->text('notes')->nullable()->after('payment_method');
        });

        // Agregar campos a order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->after('product_id');
            $table->string('size')->nullable()->after('price');
            $table->string('sugar')->nullable()->after('size');
            $table->json('toppings')->nullable()->after('sugar');
            $table->json('addons')->nullable()->after('toppings');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'phone', 'address', 'payment_method', 'notes']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['price', 'size', 'sugar', 'toppings', 'addons']);
        });
    }
};