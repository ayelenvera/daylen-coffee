<?php
// database/migrations/2025_XX_XX_XXXXXX_add_missing_fields_to_customization_rules.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_customization_rules', function (Blueprint $table) {
            $table->json('topping_options')->nullable()->after('sugar_options');
            $table->json('addon_options')->nullable()->after('topping_options');
            $table->string('default_size')->nullable()->after('size_options');
        });
    }

    public function down()
    {
        Schema::table('product_customization_rules', function (Blueprint $table) {
            $table->dropColumn(['topping_options', 'addon_options', 'default_size']);
        });
    }
};