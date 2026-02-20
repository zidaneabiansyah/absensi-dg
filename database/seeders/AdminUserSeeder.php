<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Create operator user
        User::create([
            'name' => 'Operator',
            'email' => 'operator@admin.dg',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'status' => 'active',
        ]);
    }
}
