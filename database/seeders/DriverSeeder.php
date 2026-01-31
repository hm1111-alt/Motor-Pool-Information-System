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
        // Driver data array matching the new schema
        $drivers = [
            [
                'firsts_name' => 'Irwin',
                'middle_initial' => 'J',
                'last_name' => 'Salapare',
                'contact_num' => '09175107991',
                'email' => 'irwin.salapare@example.com',
                'password' => 'password123',
                'address' => 'Sample Address 1, Manila',
                'position' => 'Admin Officer',
                'official_station' => 'MPS, Transportation Services',
                'availability_status' => 'Available',
            ],
            [
                'firsts_name' => 'Enrico',
                'middle_initial' => 'M',
                'last_name' => 'Baltazar',
                'contact_num' => '09273685031',
                'email' => 'enrico.baltazar@example.com',
                'password' => 'password123',
                'address' => 'Sample Address 2, Quezon City',
                'position' => 'Admin Assistant III',
                'official_station' => 'Motor Pool Section',
                'availability_status' => 'Available',
            ],
            [
                'firsts_name' => 'Wilson',
                'middle_initial' => 'K',
                'last_name' => 'Dacumos',
                'contact_num' => '09554263129',
                'email' => 'wilson.dacumos@example.com',
                'password' => 'password123',
                'address' => 'Sample Address 3, Makati',
                'position' => 'Admin Aide III',
                'official_station' => 'Motor Pool Section',
                'availability_status' => 'On Duty',
            ],
            [
                'firsts_name' => 'Alejandro',
                'middle_initial' => 'L',
                'last_name' => 'Catalma',
                'contact_num' => '09536943549',
                'email' => 'alejandro.catalma@example.com',
                'password' => 'password123',
                'address' => 'Sample Address 4, Pasig',
                'position' => 'Admin Aide IV',
                'official_station' => 'Motor Pool Section',
                'availability_status' => 'Available',
            ],
            [
                'firsts_name' => 'Joey',
                'middle_initial' => 'M',
                'last_name' => 'Corales',
                'contact_num' => '09274089758',
                'email' => 'joey.corales@example.com',
                'password' => 'password123',
                'address' => 'Sample Address 5, Taguig',
                'position' => 'Admin Aide III',
                'official_station' => 'Motor Pool Section',
                'availability_status' => 'Not Available',
            ],
        ];

        // Create driver users and driver records
        foreach ($drivers as $index => $driverData) {
            // Create a user for each driver
            $user = User::create([
                'name' => $driverData['firsts_name'] . ' ' . $driverData['last_name'],
                'email' => $driverData['email'],
                'password' => Hash::make($driverData['password']),
                'role' => 'driver',
            ]);

            // Create the driver record with user_id
            Driver::create([
                'user_id' => $user->id,
                'firsts_name' => $driverData['firsts_name'],
                'middle_initial' => $driverData['middle_initial'],
                'last_name' => $driverData['last_name'],
                'full_name' => $driverData['firsts_name'] . ' ' . $driverData['last_name'],
                'full_name2' => $driverData['firsts_name'] . ' ' . $driverData['middle_initial'] . '. ' . $driverData['last_name'],
                'contact_num' => $driverData['contact_num'],
                'email' => $driverData['email'],
                'password' => Hash::make($driverData['password']),
                'address' => $driverData['address'],
                'position' => $driverData['position'],
                'official_station' => $driverData['official_station'],
                'availability_status' => $driverData['availability_status'],
            ]);
        }
    }
}