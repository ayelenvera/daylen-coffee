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
        // Asegurarse de que la tabla categories tenga todas las columnas necesarias
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'name')) {
                $table->string('name');
            }
            
            if (!Schema::hasColumn('categories', 'slug')) {
                $table->string('slug')->unique();
            }
            
            if (!Schema::hasColumn('categories', 'emoji')) {
                $table->integer('emoji')->default(0);
            }
            
            if (!Schema::hasColumn('categories', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            
            // Asegurarse de que las columnas de timestamps existan
            if (!Schema::hasColumn('categories', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacemos nada en el método down para evitar pérdida de datos
    }
};
