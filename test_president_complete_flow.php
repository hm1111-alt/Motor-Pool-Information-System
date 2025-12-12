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

// Test the complete President travel order flow
try {
    echo "=== Testing Complete President Travel Order Flow ===\n\n";
    
    // Get a president employee
    $president = Employee::where('is_president', 1)->first();
    
    if (!$president) {
        echo "No president found\n";
        exit;
    }
    
    echo "President: " . $president->first_name . " " . $president->last_name . " (ID: " . $president->id . ")\n";
    echo "is_president: " . var_export($president->is_president, true) . "\n";
    
    // Mock the Auth facade
    Auth::shouldReceive('user')->andReturn((object)[
        'employee' => $president
    ]);
    
    // Create a mock request for storing a travel order
    $request = new Request();
    $request->setMethod('POST');
    $request->request->add([
        'destination' => 'Complete Flow Test Destination',
        'date_from' => '2025-12-20',
        'date_to' => '2025-12-22',
        'departure_time' => '08:00',
        'purpose' => 'Complete flow test purpose'
    ]);
    
    // Create controller instance
    $controller = new PresidentOwnTravelOrderController();
    
    // Call the store method
    echo "\n1. Creating travel order through controller...\n";
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
        
        // Now test the index method to see how it displays the travel order
        echo "\n2. Testing index method...\n";
        $indexRequest = new Request();
        $indexResponse = $controller->index($indexRequest);
        
        // Get the travel orders from the response
        $viewData = $indexResponse->getData();
        $travelOrders = $viewData['travelOrders'];
        
        echo "Found " . $travelOrders->count() . " travel orders\n";
        
        // Find our specific travel order
        $foundOrder = null;
        foreach ($travelOrders as $order) {
            if ($order->id == $travelOrder->id) {
                $foundOrder = $order;
                break;
            }
        }
        
        if ($foundOrder) {
            echo "Found our travel order in the list:\n";
            echo "  ID: " . $foundOrder->id . "\n";
            echo "  Status: " . $foundOrder->status . "\n";
            echo "  Remarks: " . $foundOrder->remarks . "\n";
            echo "  Employee is_president: " . var_export($foundOrder->employee->is_president, true) . "\n";
        } else {
            echo "Our travel order was not found in the list\n";
        }
        
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