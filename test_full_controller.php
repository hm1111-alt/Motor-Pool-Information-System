<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Itinerary;
use Illuminate\Http\Request;

// Simulate the controller request
$request = Request::create('/itinerary', 'GET', ['tab' => 'pending']);
$tab = $request->get('tab', 'pending');

// Base query
$query = Itinerary::with(['travelOrder', 'vehicle', 'driver']);

// Apply tab filtering based on status field
switch ($tab) {
    case 'approved':
        $query->where('status', 'Approved');
        break;
    case 'cancelled':
        $query->where('status', 'Cancelled');
        break;
    case 'pending':
    default:
        $query->where('status', 'Not yet Approved');
        break;
}

$itineraries = $query->orderBy('date_from', 'desc')
    ->orderBy('departure_time', 'asc')
    ->paginate(10);

// Get counts for each tab based on status field
$pendingCount = Itinerary::where('status', 'Not yet Approved')->count();
$approvedCount = Itinerary::where('status', 'Approved')->count();
$cancelledCount = Itinerary::where('status', 'Cancelled')->count();

echo "=== Controller Output Test ===\n";
echo "Tab: " . $tab . "\n";
echo "Itineraries count: " . $itineraries->count() . "\n";
echo "Pending count: " . $pendingCount . "\n";
echo "Approved count: " . $approvedCount . "\n";
echo "Cancelled count: " . $cancelledCount . "\n";

echo "\nItineraries data:\n";
foreach($itineraries as $itinerary) {
    echo "ID: " . $itinerary->id . " | Status: " . $itinerary->status . " | Destination: " . $itinerary->destination . "\n";
}