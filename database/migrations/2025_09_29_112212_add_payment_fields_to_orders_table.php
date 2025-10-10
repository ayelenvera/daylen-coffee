<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->after('status');
            $table->string('card_issuer')->nullable()->after('payment_status');
            $table->string('card_last_four')->nullable()->after('card_issuer');
            $table->string('card_expiration')->nullable()->after('card_last_four');
            $table->text('payment_response')->nullable()->after('card_expiration');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'card_issuer',
                'card_last_four', 
                'card_expiration',
                'payment_response'
            ]);
        });
    }
};