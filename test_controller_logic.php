<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Itinerary;
use Illuminate\Http\Request;

// Test the controller logic directly
echo "=== Testing Controller Logic ===\n\n";

// Test pending tab
echo "PENDING TAB DATA:\n";
$pendingQuery = Itinerary::with(['travelOrder', 'vehicle', 'driver'])
    ->where('status', 'Not yet Approved');
$pendingItineraries = $pendingQuery->get();

foreach($pendingItineraries as $itinerary) {
    echo "ID: " . $itinerary->id . " | Status: " . $itinerary->status . " | Destination: " . $itinerary->destination . "\n";
}

echo "\nAPPROVED TAB DATA:\n";
$approvedQuery = Itinerary::with(['travelOrder', 'vehicle', 'driver'])
    ->where('status', 'Approved');
$approvedItineraries = $approvedQuery->get();

foreach($approvedItineraries as $itinerary) {
    echo "ID: " . $itinerary->id . " | Status: " . $itinerary->status . " | Destination: " . $itinerary->destination . "\n";
}

echo "\nCANCELLED TAB DATA:\n";
$cancelledQuery = Itinerary::with(['travelOrder', 'vehicle', 'driver'])
    ->where('status', 'Cancelled');
$cancelledItineraries = $cancelledQuery->get();

foreach($cancelledItineraries as $itinerary) {
    echo "ID: " . $itinerary->id . " | Status: " . $itinerary->status . " | Destination: " . $itinerary->destination . "\n";
}

echo "\n=== COUNTS ===\n";
echo "Pending: " . Itinerary::where('status', 'Not yet Approved')->count() . "\n";
echo "Approved: " . Itinerary::where('status', 'Approved')->count() . "\n";
echo "Cancelled: " . Itinerary::where('status', 'Cancelled')->count() . "\n";