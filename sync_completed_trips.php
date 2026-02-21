<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use App\Models\TripTicket;
use App\Models\VehicleTravelHistory;

// Bootstrap the application
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Syncing completed trip tickets to vehicle travel history...\n";

// Find all completed trip tickets that don't have corresponding vehicle travel history records
$completedTripTickets = TripTicket::where('status', 'Completed')
    ->whereDoesntHave('travelHistory') // Only those without existing travel history
    ->with(['itinerary.vehicle', 'itinerary.driver'])
    ->get();

$counter = 0;

foreach ($completedTripTickets as $tripTicket) {
    // Make sure the itinerary exists and has required relationships
    if (!$tripTicket->itinerary || !$tripTicket->itinerary->vehicle) {
        echo "Skipping trip ticket {$tripTicket->id}: missing itinerary or vehicle\n";
        continue;
    }

    // Prepare data for vehicle travel history
    $data = [
        'trip_ticket_id' => $tripTicket->id,
        'vehicle_id' => $tripTicket->itinerary->vehicle_id,
        'driver_id' => $tripTicket->itinerary->driver_id,
        'head_of_party' => $tripTicket->head_of_party,
        'destination' => $tripTicket->itinerary->destination ?? 'N/A',
        'departure_date' => $tripTicket->itinerary->date_from ?? now(),
        'departure_time' => $tripTicket->itinerary->departure_time,
        'arrival_date' => $tripTicket->itinerary->date_to,
        'arrival_time' => $tripTicket->itinerary->departure_time, // Using departure time as default
        'distance_km' => null, // Will be calculated by the observer or service
        'remarks' => 'Auto-generated from existing completed trip ticket',
    ];

    // Create the vehicle travel history record
    VehicleTravelHistory::create($data);
    $counter++;
    
    echo "Created travel history for trip ticket {$tripTicket->id}\n";
}

echo "Sync completed. Created {$counter} vehicle travel history records.\n";
echo "Run 'php artisan optimize:clear' to clear caches if needed.\n";