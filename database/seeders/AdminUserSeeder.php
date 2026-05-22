<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bellezaaurea.com'],
            [
                'name' => 'Administrador Belleza Áurea',
                'password' => Hash::make('aurea2026'),
                'is_admin' => true,
            ]
        );
    }
}
