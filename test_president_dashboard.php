<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use App\Http\Controllers\PresidentTravelOrderController;
use Illuminate\Http\Request;

// Test the President dashboard logic for division head travel orders
try {
    echo "=== Testing President Dashboard for Division Head Travel Orders ===\n\n";
    
    // Get a president employee
    $president = Employee::where('is_president', 1)->first();
    
    if (!$president) {
        echo "No president found\n";
        exit;
    }
    
    echo "President: " . $president->first_name . " " . $president->last_name . " (ID: " . $president->id . ")\n";
    
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
    
    // Mock the Auth facade
    \Illuminate\Support\Facades\Auth::shouldReceive('user')->andReturn((object)[
        'employee' => $president
    ]);
    
    // Create controller instance
    $controller = new PresidentTravelOrderController();
    
    // Test Pending Tab - Should show division head travel orders that are VP approved
    echo "\nTesting Pending Tab for Division Head Travel Orders:\n";
    $pendingRequest = new Request();
    $pendingRequest->merge(['tab' => 'pending']);
    
    $pendingResponse = $controller->index($pendingRequest);
    $pendingTravelOrders = $pendingResponse->getData()['travelOrders'];
    
    echo "Found " . count($pendingTravelOrders) . " travel orders in Pending tab:\n";
    
    $foundDivisionHeadRequest = false;
    foreach ($pendingTravelOrders as $order) {
        if ($order->id == $travelOrder->id) {
            echo "  ✓ Found our test division head travel order ID: " . $order->id . "\n";
            echo "    Employee: " . $order->employee->first_name . " " . $order->employee->last_name . "\n";
            echo "    VP Approved: " . var_export($order->vp_approved, true) . "\n";
            echo "    Remarks: " . $order->remarks . "\n";
            $foundDivisionHeadRequest = true;
        }
    }
    
    if (!$foundDivisionHeadRequest) {
        echo "  ✗ Our test division head travel order not found in Pending tab\n";
    }
    
    // Clean up - delete the test travel order
    $travelOrder->delete();
    
    echo "\nTest completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}