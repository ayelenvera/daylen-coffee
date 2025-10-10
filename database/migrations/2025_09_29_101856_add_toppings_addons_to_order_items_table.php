<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->json('toppings')->nullable()->after('sugar');
            $table->json('addons')->nullable()->after('toppings');
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['toppings', 'addons']);
        });
    }
};