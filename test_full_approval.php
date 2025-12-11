<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use App\Http\Controllers\DivisionHeadTravelOrderController;
use Illuminate\Http\Request;

// Test the full approval process
try {
    // Get a head's travel order that hasn't been approved by division head yet
    $travelOrder = TravelOrder::whereHas('employee', function ($query) {
        $query->where('is_head', 1);
    })->whereNull('divisionhead_approved')->first();
    
    if (!$travelOrder) {
        echo "No unapproved head travel order found\n";
        exit;
    }
    
    echo "Found travel order ID: " . $travelOrder->id . "\n";
    echo "Current divisionhead_approved: " . var_export($travelOrder->divisionhead_approved, true) . "\n";
    
    // Get a division head from the same division
    $divisionHead = Employee::where('division_id', $travelOrder->employee->division_id)
        ->where('is_divisionhead', 1)
        ->first();
        
    if (!$divisionHead) {
        echo "No division head found in the same division\n";
        exit;
    }
    
    echo "Found division head ID: " . $divisionHead->id . "\n";
    
    // Create a mock request
    $request = new Request();
    
    // Mock the Auth facade by temporarily setting the user
    \Illuminate\Support\Facades\Auth::shouldReceive('user')->andReturn((object)[
        'employee' => $divisionHead
    ]);
    
    // Create controller instance
    $controller = new DivisionHeadTravelOrderController();
    
    // Test the approve method
    echo "Calling approve method...\n";
    $response = $controller->approve($travelOrder);
    
    // Refresh the travel order to see the changes
    $travelOrder->refresh();
    
    echo "After approval:\n";
    echo "divisionhead_approved: " . var_export($travelOrder->divisionhead_approved, true) . "\n";
    echo "divisionhead_approved_at: " . var_export($travelOrder->divisionhead_approved_at, true) . "\n";
    echo "status: " . $travelOrder->status . "\n";
    
    echo "Approval successful!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}