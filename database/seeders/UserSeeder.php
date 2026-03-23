<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@dsl.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true
        ]);

        // Create Warehouse Manager User
        User::create([
            'name' => 'Warehouse Manager',
            'email' => 'warehouse@dsl.com',
            'password' => Hash::make('password'),
            'role' => 'warehouse_manager',
            'is_active' => true
        ]);

        // Create Operations Officer User
        User::create([
            'name' => 'Operations Officer',
            'email' => 'operations@dsl.com',
            'password' => Hash::make('password'),
            'role' => 'operations_officer',
            'is_active' => true
        ]);
    }
}

