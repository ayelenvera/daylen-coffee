<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario ADMINISTRADOR
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear usuario NORMAL
        User::create([
            'name' => 'Usuario Normal', 
            'email' => 'user@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('user1234'),
            'is_admin' => false,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ Usuarios de prueba creados exitosamente!');
        $this->command->info('🔧 Admin: admin@test.com / admin123');
        $this->command->info('👤 User: user@test.com / user1234');
    }
}