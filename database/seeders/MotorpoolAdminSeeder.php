<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MotorpoolAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a motorpool admin user
        DB::table('users')->insert([
            'name' => 'Motorpool Admin',
            'email' => 'motorpool@admin.com',
            'password' => Hash::make('password123'),
            'role' => 'motorpool_admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        echo "Motorpool admin user created successfully!\n";
        echo "Email: motorpool@admin.com\n";
        echo "Password: password123\n";
    }
}