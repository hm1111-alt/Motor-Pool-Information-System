<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Itinerary;
use App\Models\User;
use Carbon\Carbon;

class ItinerarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            return;
        }
        
        $statuses = ['pending', 'approved', 'cancelled'];
        
        for ($i = 0; $i < 10; $i++) {
            Itinerary::create([
                'title' => 'Meeting at ' . ['Office A', 'Office B', 'Conference Room'][rand(0, 2)],
                'description' => 'Regular meeting with department heads',
                'date' => Carbon::now()->addDays(rand(1, 30)),
                'start_time' => Carbon::createFromTime(rand(8, 16), 0, 0),
                'end_time' => Carbon::createFromTime(rand(17, 20), 0, 0),
                'destination' => ['Manila', 'Quezon City', 'Makati'][rand(0, 2)],
                'purpose' => 'Business meeting with stakeholders',
                'vehicle_id' => null,
                'driver_id' => null,
                'created_by' => $users->random()->id,
                'status' => $statuses[rand(0, 2)],
            ]);
        }
    }
}