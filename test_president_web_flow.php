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
use Illuminate\Support\Facades\DB;

// Test that simulates the actual web flow for a President
try {
    echo "=== PRESIDENT WEB FLOW TEST ===\n\n";
    
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
    
    // Create controller instance
    $controller = new PresidentOwnTravelOrderController();
    
    // Simulate the create method (should now return the president-specific form)
    echo "\n1. Testing create method...\n";
    $createView = $controller->create();
    echo "Create view name: " . $createView->getName() . "\n";
    
    // Create a request that mimics what would come from the president's form
    $request = Request::create('/president/travel-orders', 'POST', [
        'destination' => 'Web Flow Test',
        'date_from' => '2026-01-05',
        'date_to' => '2026-01-07',
        'purpose' => 'Web flow test purpose',
        '_token' => 'fake_token'
    ]);
    
    // Manually set the request method
    $request->setMethod('POST');
    
    echo "\nRequest data:\n";
    print_r($request->all());
    
    // Call the store method
    echo "\n2. Calling store method...\n";
    $response = $controller->store($request);
    
    // Get the last created travel order for this president
    $travelOrder = TravelOrder::where('employee_id', $president->id)
        ->orderBy('created_at', 'desc')
        ->first();
    
    if ($travelOrder) {
        echo "Created travel order ID: " . $travelOrder->id . "\n";
        
        // Check database immediately
        echo "\n--- DATABASE CHECK ---\n";
        $dbRecord = DB::table('travel_orders')->where('id', $travelOrder->id)->first();
        echo "Database status: " . $dbRecord->status . "\n";
        echo "Database president_approved: " . var_export($dbRecord->president_approved, true) . "\n";
        echo "Database president_approved_at: " . $dbRecord->president_approved_at . "\n";
        
        // Check model
        echo "\n--- MODEL CHECK ---\n";
        $travelOrder->refresh();
        echo "Model status: " . $travelOrder->status . "\n";
        echo "Model president_approved: " . var_export($travelOrder->president_approved, true) . "\n";
        echo "Model remarks: " . $travelOrder->remarks . "\n";
        
        // Test the index method to see how it displays the travel order
        echo "\n3. Testing index method...\n";
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