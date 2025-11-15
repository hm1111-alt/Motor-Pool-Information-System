<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Driver data array
        $drivers = [
            [
                'first_name' => 'Irwin',
                'last_name' => 'Salapare',
                'middle_initial' => null,
                'ext_name' => null,
                'full_name' => 'Irwin Salapare',
                'full_name2' => 'Salapare, Irwin',
                'sex' => null,
                'contact_number' => '09175107991',
                'position' => 'Admin Officer',
                'official_station' => 'MPS, Transportation Services',
                'availability_status' => 'Active',
            ],
            [
                'first_name' => 'Enrico',
                'last_name' => 'Baltazar',
                'middle_initial' => null,
                'ext_name' => null,
                'full_name' => 'Enrico Baltazar',
                'full_name2' => 'Baltazar, Enrico',
                'sex' => null,
                'contact_number' => '09273685031',
                'position' => 'Admin Assistant III',
                'official_station' => 'Motor Pool Section',
                'availability_status' => 'Active',
            ],
            [
                'first_name' => 'Wilson',
                'last_name' => 'Dacumos',
                'middle_initial' => null,
                'ext_name' => null,
                'full_name' => 'Wilson Dacumos',
                'full_name2' => 'Dacumos, Wilson',
                'sex' => null,
                'contact_number' => '09554263129',
                'position' => 'Admin Aide III',
                'official_station' => 'Motor Pool Section',
                'availability_status' => 'Active',
            ],
            [
                'first_name' => 'Alejandro',
                'last_name' => 'Catalma',
                'middle_initial' => null,
                'ext_name' => null,
                'full_name' => 'Alejandro Catalma',
                'full_name2' => 'Catalma, Alejandro',
                'sex' => null,
                'contact_number' => '09536943549',
                'position' => 'Admin Aide IV',
                'official_station' => 'Motor Pool Section',
                'availability_status' => 'Active',
            ],
            [
                'first_name' => 'Joey',
                'last_name' => 'Corales',
                'middle_initial' => null,
                'ext_name' => null,
                'full_name' => 'Joey Corales',
                'full_name2' => 'Corales, Joey',
                'sex' => null,
                'contact_number' => '09274089758',
                'position' => 'Admin Aide III',
                'official_station' => 'Motor Pool Section',
                'availability_status' => 'Active',
            ],
        ];

        // Create driver users and driver records
        foreach ($drivers as $index => $driverData) {
            // Create a user for each driver
            $user = User::create([
                'name' => $driverData['first_name'] . ' ' . $driverData['last_name'],
                'email' => strtolower($driverData['first_name'] . '.' . $driverData['last_name']) . '@example.com',
                'contact_num' => $driverData['contact_number'],
                'password' => Hash::make('password123'),
                'role' => 'driver',
            ]);

            // Create the driver record (without user_id since it's not in the schema)
            Driver::create($driverData);
        }
    }
}