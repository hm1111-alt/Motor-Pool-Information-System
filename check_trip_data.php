<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TripTicket;
use App\Models\Itinerary;

echo "=== Trip Tickets and Their Itineraries ===\n\n";

$tripTickets = TripTicket::with('itinerary.vehicle')->get();

foreach($tripTickets as $ticket) {
    echo "Trip Ticket ID: " . $ticket->id . "\n";
    echo "Itinerary ID: " . $ticket->itinerary_id . "\n";
    echo "Status: " . $ticket->status . "\n";
    
    if($ticket->itinerary) {
        echo "Itinerary Date From: " . $ticket->itinerary->date_from . "\n";
        echo "Itinerary Date To: " . $ticket->itinerary->date_to . "\n";
        echo "Destination: " . $ticket->itinerary->destination . "\n";
        echo "Vehicle: " . ($ticket->itinerary->vehicle ? $ticket->itinerary->vehicle->make . ' ' . $ticket->itinerary->vehicle->model : 'No vehicle') . "\n";
        echo "Plate: " . ($ticket->itinerary->vehicle ? $ticket->itinerary->vehicle->plate_number : 'N/A') . "\n";
    } else {
        echo "NO ITINERARY FOUND!\n";
    }
    echo "---\n";
}

echo "\n=== All Itineraries ===\n\n";
$itineraries = Itinerary::all();
foreach($itineraries as $itinerary) {
    echo "Itinerary ID: " . $itinerary->id . "\n";
    echo "Date From: " . $itinerary->date_from . "\n";
    echo "Date To: " . $itinerary->date_to . "\n";
    echo "Destination: " . $itinerary->destination . "\n";
    echo "Vehicle ID: " . $itinerary->vehicle_id . "\n";
    echo "---\n";
}