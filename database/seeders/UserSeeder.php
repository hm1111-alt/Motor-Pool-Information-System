<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear the users table
        DB::table('users')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert test users
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'contact_num' => '09123456789',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Motorpool Admin',
                'email' => 'motorpooladmin@example.com',
                'contact_num' => '09123456780',
                'password' => Hash::make('password123'),
                'role' => 'motorpool_admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Driver User',
                'email' => 'driver@example.com',
                'contact_num' => '09123456781',
                'password' => Hash::make('password123'),
                'role' => 'driver',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Employee User',
                'email' => 'employee@example.com',
                'contact_num' => '09123456782',
                'password' => Hash::make('password123'),
                'role' => 'employees',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // New employee users
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@example.com',
                'contact_num' => '09123456783',
                'password' => Hash::make('password123'),
                'role' => 'employees',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan.delacruz@example.com',
                'contact_num' => '09123456784',
                'password' => Hash::make('password123'),
                'role' => 'employees',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ana Reyes',
                'email' => 'ana.reyes@example.com',
                'contact_num' => '09123456785',
                'password' => Hash::make('password123'),
                'role' => 'employees',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Carlos Garcia',
                'email' => 'carlos.garcia@example.com',
                'contact_num' => '09123456786',
                'password' => Hash::make('password123'),
                'role' => 'employees',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}