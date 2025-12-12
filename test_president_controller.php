<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use App\Http\Controllers\PresidentOwnTravelOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Test the President controller directly
try {
    echo "=== Testing President Controller Directly ===\n\n";
    
    // Get a president employee
    $president = Employee::where('is_president', 1)->first();
    
    if (!$president) {
        echo "No president found\n";
        exit;
    }
    
    echo "President: " . $president->first_name . " " . $president->last_name . " (ID: " . $president->id . ")\n";
    
    // Mock the Auth facade
    Auth::shouldReceive('user')->andReturn((object)[
        'employee' => $president
    ]);
    
    // Create a mock request
    $request = new Request();
    $request->setMethod('POST');
    $request->request->add([
        'destination' => 'Test Destination',
        'date_from' => '2025-12-15',
        'date_to' => '2025-12-16',
        'purpose' => 'Test purpose from controller test'
    ]);
    
    // Validate the request data
    $request->validate([
        'destination' => 'required|string|max:255',
        'date_from' => 'required|date|before_or_equal:date_to',
        'date_to' => 'required|date|after_or_equal:date_from',
        'purpose' => 'required|string|max:500',
    ]);
    
    // Create controller instance
    $controller = new PresidentOwnTravelOrderController();
    
    // Call the store method
    echo "Calling controller store method...\n";
    $response = $controller->store($request);
    
    // Get the last created travel order for this president
    $travelOrder = TravelOrder::where('employee_id', $president->id)
        ->orderBy('created_at', 'desc')
        ->first();
    
    if ($travelOrder) {
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
    } else {
        echo "No travel order found\n";
    }
    
    echo "\nTest completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}