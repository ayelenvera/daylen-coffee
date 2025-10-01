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
        Schema::create('shop_settings', function (Blueprint $table) {
            $table->id();
            
            // Información básica de la tienda
            $table->string('shop_name')->default('Daylen Cafetería');
            $table->string('logo_url')->nullable();
            $table->string('email')->default('daylencoffee@gmail.com');
            $table->string('phone')->default('+595 986 195914');
            $table->text('address')->default('Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este');
            $table->string('ruc')->default('12345678-9');
            
            // Contenido dinámico
            $table->text('about_us')->nullable();
            
            // Configuraciones adicionales
            $table->string('primary_color')->default('#d97706'); // amber-600
            $table->string('secondary_color')->default('#f59e0b'); // amber-500
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });

        // Insertar configuración por defecto
        DB::table('shop_settings')->insert([
            'shop_name' => 'Daylen Cafetería',
            'email' => 'daylencoffee@gmail.com',
            'phone' => '+595 986 195914',
            'address' => 'Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este',
            'ruc' => '12345678-9',
            'about_us' => 'Somos una cafetería dedicada a ofrecer los mejores productos con ingredientes de calidad. Nuestro compromiso es brindarte una experiencia única en cada visita.',
            'primary_color' => '#d97706',
            'secondary_color' => '#f59e0b',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_settings');
    }
};