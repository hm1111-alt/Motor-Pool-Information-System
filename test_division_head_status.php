<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;

// Test the division head status logic after VP approval
try {
    echo "=== Testing Division Head Status After VP Approval ===\n\n";
    
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
    echo "Initial status: " . $travelOrder->status . "\n";
    echo "Initial remarks: " . $travelOrder->remarks . "\n";
    
    // Simulate VP approval
    $travelOrder->update([
        'vp_approved' => true,
        'vp_approved_at' => now(),
        'status' => 'pending'  // This should be set by our logic
    ]);
    
    // Refresh the travel order to get updated status and remarks
    $travelOrder->refresh();
    
    echo "After VP approval:\n";
    echo "VP Approved: " . var_export($travelOrder->vp_approved, true) . "\n";
    echo "Status: " . $travelOrder->status . "\n";
    echo "Remarks: " . $travelOrder->remarks . "\n";
    
    // Verify the status is pending and remarks are "For President approval"
    if ($travelOrder->status === 'pending' && $travelOrder->remarks === 'For President approval') {
        echo "\n✓ Test PASSED: Status is pending and remarks are 'For President approval'\n";
    } else {
        echo "\n✗ Test FAILED: Status is '" . $travelOrder->status . "' and remarks are '" . $travelOrder->remarks . "'\n";
    }
    
    // Clean up - delete the test travel order
    $travelOrder->delete();
    
    echo "\nTest completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}