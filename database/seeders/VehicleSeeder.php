<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vehicle data array with realistic information
        $vehicles = [
            [
                'plate_number' => 'ABC-123',
                'model' => 'Toyota Innova',
                'type' => 'SUV',
                'seating_capacity' => 8,
                'mileage' => 25000,
                'status' => 'Available',
            ],
            [
                'plate_number' => 'XYZ-789',
                'model' => 'Honda City',
                'type' => 'Sedan',
                'seating_capacity' => 5,
                'mileage' => 18500,
                'status' => 'Available',
            ],
            [
                'plate_number' => 'DEF-456',
                'model' => 'Nissan X-Trail',
                'type' => 'SUV',
                'seating_capacity' => 7,
                'mileage' => 32000,
                'status' => 'Under Maintenance',
            ],
            [
                'plate_number' => 'GHI-321',
                'model' => 'Mitsubishi Mirage',
                'type' => 'Hatchback',
                'seating_capacity' => 5,
                'mileage' => 15200,
                'status' => 'Available',
            ],
            [
                'plate_number' => 'JKL-654',
                'model' => 'Ford Everest',
                'type' => 'SUV',
                'seating_capacity' => 7,
                'mileage' => 41500,
                'status' => 'Active',
            ],
            [
                'plate_number' => 'MNO-987',
                'model' => 'Hyundai Tucson',
                'type' => 'SUV',
                'seating_capacity' => 5,
                'mileage' => 28700,
                'status' => 'Available',
            ],
        ];

        // Create vehicle records
        foreach ($vehicles as $vehicleData) {
            Vehicle::create($vehicleData);
        }
    }
}