<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call UserSeeder to insert test users
        $this->call(UserSeeder::class);
        
        // Call OfficeSeeder to insert office data
        $this->call(OfficeSeeder::class);
        
        // Call EmployeeSeeder to insert employee data
        $this->call(EmployeeSeeder::class);
        
        // Call DriverSeeder to insert driver data
        $this->call(DriverSeeder::class);
        
        // Call VehicleSeeder to insert vehicle data
        $this->call(VehicleSeeder::class);
    }
}