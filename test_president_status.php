<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;

// Test the President travel order status
try {
    echo "=== Testing President Travel Order Status ===\n\n";
    
    // Get a president employee
    $president = Employee::where('is_president', 1)->first();
    
    if (!$president) {
        echo "No president found\n";
        exit;
    }
    
    echo "President: " . $president->first_name . " " . $president->last_name . " (ID: " . $president->id . ")\n";
    
    // Create a travel order for this president with the exact same parameters as the controller
    $travelOrder = TravelOrder::create([
        'employee_id' => $president->id,
        'destination' => 'Test Destination',
        'date_from' => '2025-12-15',
        'date_to' => '2025-12-16',
        'purpose' => 'Test purpose',
        'status' => 'approved', // Should be automatically set to approved
        'president_approved' => true, // Should be automatically set to true
        'president_approved_at' => now(), // Should be automatically set
    ]);
    
    echo "Created travel order ID: " . $travelOrder->id . "\n";
    echo "Status: " . $travelOrder->status . "\n";
    echo "President Approved: " . var_export($travelOrder->president_approved, true) . "\n";
    echo "President Approved At: " . ($travelOrder->president_approved_at ? $travelOrder->president_approved_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
    echo "Remarks: " . $travelOrder->remarks . "\n";
    
    // Refresh the model to make sure we're getting the latest data
    $travelOrder->refresh();
    
    echo "\nAfter refresh:\n";
    echo "Status: " . $travelOrder->status . "\n";
    echo "President Approved: " . var_export($travelOrder->president_approved, true) . "\n";
    echo "Remarks: " . $travelOrder->remarks . "\n";
    
    // Clean up - delete the test travel order
    $travelOrder->delete();
    
    echo "\nTest completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}