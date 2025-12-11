<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;

// Test if we can update a travel order with division head approval
try {
    // Get the first travel order
    $travelOrder = TravelOrder::first();
    
    if ($travelOrder) {
        echo "Found travel order ID: " . $travelOrder->id . "\n";
        echo "Current divisionhead_approved: " . var_export($travelOrder->divisionhead_approved, true) . "\n";
        
        // Try to update division head approval
        $travelOrder->divisionhead_approved = true;
        $travelOrder->divisionhead_approved_at = now();
        $travelOrder->save();
        
        echo "Successfully updated division head approval\n";
        echo "New divisionhead_approved: " . var_export($travelOrder->divisionhead_approved, true) . "\n";
    } else {
        echo "No travel orders found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}