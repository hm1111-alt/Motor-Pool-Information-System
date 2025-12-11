<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use App\Http\Controllers\VpTravelOrderController;
use Illuminate\Http\Request;

// Test the VP dashboard logic
try {
    // Get a VP employee
    $vpEmployee = Employee::where('is_vp', 1)->first();
    
    if (!$vpEmployee) {
        echo "No VP found\n";
        exit;
    }
    
    echo "Found VP ID: " . $vpEmployee->id . " in office ID: " . $vpEmployee->office_id . "\n";
    
    // Create a mock request with pending tab
    $request = new Request();
    $request->merge(['tab' => 'pending']);
    
    // Mock the Auth facade by temporarily setting the user
    \Illuminate\Support\Facades\Auth::shouldReceive('user')->andReturn((object)[
        'employee' => $vpEmployee
    ]);
    
    // Create controller instance
    $controller = new VpTravelOrderController();
    
    // Test the index method
    echo "Calling VP index method...\n";
    $response = $controller->index($request);
    
    // Get the travel orders that would be displayed
    $travelOrders = $response->getData()['travelOrders'];
    
    echo "Found " . count($travelOrders) . " travel orders pending VP approval:\n";
    
    foreach ($travelOrders as $travelOrder) {
        echo "- Travel Order ID: " . $travelOrder->id . "\n";
        echo "  Employee: " . $travelOrder->employee->first_name . " " . $travelOrder->employee->last_name . "\n";
        echo "  Is Head: " . ($travelOrder->employee->is_head ? 'Yes' : 'No') . "\n";
        if ($travelOrder->employee->is_head) {
            echo "  Division Head Approved: " . var_export($travelOrder->divisionhead_approved, true) . "\n";
            echo "  Division Head Approved At: " . ($travelOrder->divisionhead_approved_at ? $travelOrder->divisionhead_approved_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
        } else {
            echo "  Head Approved: " . var_export($travelOrder->head_approved, true) . "\n";
            echo "  Head Approved At: " . ($travelOrder->head_approved_at ? $travelOrder->head_approved_at->format('Y-m-d H:i:s') : 'N/A') . "\n";
        }
        echo "  VP Approved: " . var_export($travelOrder->vp_approved, true) . "\n";
        echo "  Remarks: " . $travelOrder->remarks . "\n";
        echo "\n";
    }
    
    echo "VP dashboard test completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}