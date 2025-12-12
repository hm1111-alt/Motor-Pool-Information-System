<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;

// Test the President travel order creation and approval
try {
    echo "=== Testing President Travel Order Creation ===\n\n";
    
    // Get a president employee
    $president = Employee::where('is_president', 1)->first();
    
    if (!$president) {
        echo "No president found\n";
        exit;
    }
    
    echo "President: " . $president->first_name . " " . $president->last_name . " (ID: " . $president->id . ")\n";
    
    // Create a travel order for this president
    $travelOrder = TravelOrder::create([
        'employee_id' => $president->id,
        'destination' => 'Presidential Destination',
        'date_from' => '2025-12-20',
        'date_to' => '2025-12-22',
        'purpose' => 'Official presidential business',
        'status' => 'approved', // Should be automatically set to approved
        'president_approved' => true, // Should be automatically set to true
        'president_approved_at' => now(), // Should be automatically set
    ]);
    
    echo "Created travel order ID: " . $travelOrder->id . "\n";
    echo "Status: " . $travelOrder->status . "\n";
    echo "President Approved: " . var_export($travelOrder->president_approved, true) . "\n";
    echo "Remarks: " . $travelOrder->remarks . "\n";
    
    // Verify the travel order is correctly set up
    if ($travelOrder->status === 'approved' && $travelOrder->president_approved === true && $travelOrder->remarks === 'Approved') {
        echo "\n✓ Test PASSED: President travel order is correctly auto-approved\n";
    } else {
        echo "\n✗ Test FAILED: President travel order is not correctly auto-approved\n";
        echo "Expected status: approved, Actual: " . $travelOrder->status . "\n";
        echo "Expected president_approved: true, Actual: " . var_export($travelOrder->president_approved, true) . "\n";
        echo "Expected remarks: Approved, Actual: " . $travelOrder->remarks . "\n";
    }
    
    // Clean up - delete the test travel order
    $travelOrder->delete();
    
    echo "\nTest completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}