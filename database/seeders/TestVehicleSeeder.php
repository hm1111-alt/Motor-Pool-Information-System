<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class TestVehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vehicle::create([
            'plate_number' => 'ABC-123',
            'model' => 'Toyota Camry',
            'type' => 'Sedan',
            'seating_capacity' => 5,
            'mileage' => 25000,
            'status' => 'Available'
        ]);

        Vehicle::create([
            'plate_number' => 'XYZ-789',
            'model' => 'Honda Civic',
            'type' => 'Sedan',
            'seating_capacity' => 5,
            'mileage' => 30000,
            'status' => 'Active'
        ]);

        Vehicle::create([
            'plate_number' => 'DEF-456',
            'model' => 'Ford Transit',
            'type' => 'Van',
            'seating_capacity' => 15,
            'mileage' => 45000,
            'status' => 'Under Maintenance'
        ]);
    }
}