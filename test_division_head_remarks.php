<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;

// Test the division head remarks logic
try {
    echo "=== Testing Division Head Remarks After VP Approval ===\n\n";
    
    // Get a division head employee
    $divisionHead = Employee::where('is_divisionhead', 1)->first();
    
    if (!$divisionHead) {
        echo "No division head found\n";
        exit;
    }
    
    echo "Division Head: " . $divisionHead->first_name . " " . $divisionHead->last_name . " (ID: " . $divisionHead->id . ")\n";
    
    // Create a travel order for this division head
    $travelOrder = TravelOrder::create([
        'employee_id' => $divisionHead->id,
        'destination' => 'Test Destination',
        'date_from' => '2025-12-15',
        'date_to' => '2025-12-16',
        'purpose' => 'Test purpose for division head travel order',
        'status' => 'pending'
    ]);
    
    echo "Created travel order ID: " . $travelOrder->id . "\n";
    echo "Initial remarks: " . $travelOrder->remarks . "\n";
    
    // Simulate VP approval
    $travelOrder->update([
        'vp_approved' => true,
        'vp_approved_at' => now(),
        'status' => 'approved'
    ]);
    
    // Refresh the travel order to get updated remarks
    $travelOrder->refresh();
    
    echo "After VP approval:\n";
    echo "VP Approved: " . var_export($travelOrder->vp_approved, true) . "\n";
    echo "Remarks: " . $travelOrder->remarks . "\n";
    
    // Clean up - delete the test travel order
    $travelOrder->delete();
    
    echo "\nTest completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}