<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Usuario Administrador
        User::updateOrCreate(
            ['email' => 'admin@admin.com'], // Busca por email
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'), // Cambia esto después
                'role'     => 'admin',
            ]
        );

        // 2. Crear Usuario Lector (Empleado)
        User::updateOrCreate(
            ['email' => 'lector@corporatehrp.com'],
            [
                'name'     => 'User',
                'password' => Hash::make('User123'),
                'role'     => 'user',
            ]
        );
    }
}
