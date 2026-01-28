<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if an admin already exists
        $existingAdmin = DB::table('users')->where('role', 'admin')->first();
        
        if ($existingAdmin) {
            echo "Admin already exists: " . $existingAdmin->email . "\n";
            return;
        }

        // Create a regular admin user
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        echo "Admin user created successfully!\n";
        echo "Email: admin@example.com\n";
        echo "Password: password123\n";
    }
}