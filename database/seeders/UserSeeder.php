<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@yayafood.app',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // Restaurant User
        User::create([
            'name' => 'Gérant Assiette d\'Or',
            'email' => 'resto1@yayafood.app',
            'password' => Hash::make('password'),
            'role' => 'restaurant',
            'restaurant_id' => 1,
        ]);

        // Client User
        User::create([
            'name' => 'Jean Client',
            'email' => 'client@yayafood.app',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);
    }
}
