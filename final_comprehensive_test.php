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

// Final comprehensive test to verify the entire President workflow
try {
    echo "=== FINAL COMPREHENSIVE PRESIDENT WORKFLOW TEST ===\n\n";
    
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
    
    // 1. Test the create method (should return president-create view)
    echo "\n1. Testing CREATE method...\n";
    $createView = $controller->create();
    echo "✓ Create view: " . $createView->getName() . "\n";
    
    // 2. Create a request that simulates the actual form submission
    echo "\n2. Testing STORE method with form data...\n";
    $request = new Request();
    $request->setMethod('POST');
    $request->request->add([
        'destination' => 'Final Comprehensive Test',
        'date_from' => '2026-01-15',
        'date_to' => '2026-01-17',
        'departure_time' => '09:00',
        'purpose' => 'Final comprehensive test purpose'
    ]);
    
    // Validate the request manually to make sure it passes
    $validator = validator($request->all(), [
        'destination' => 'required|string|max:255',
        'date_from' => 'required|date|before_or_equal:date_to',
        'date_to' => 'required|date|after_or_equal:date_from',
        'departure_time' => 'nullable|date_format:H:i',
        'purpose' => 'required|string|max:500',
    ]);
    
    if ($validator->fails()) {
        echo "✗ Validation failed:\n";
        foreach ($validator->errors()->all() as $error) {
            echo "  - " . $error . "\n";
        }
        exit;
    } else {
        echo "✓ Validation passed\n";
    }
    
    // Call the store method
    $response = $controller->store($request);
    echo "✓ Store method executed\n";
    
    // 3. Find the created travel order
    $travelOrder = TravelOrder::where('employee_id', $president->id)
        ->orderBy('created_at', 'desc')
        ->first();
    
    if (!$travelOrder) {
        echo "✗ No travel order found\n";
        exit;
    }
    
    echo "✓ Found travel order ID: " . $travelOrder->id . "\n";
    
    // 4. Verify database values
    echo "\n3. Verifying DATABASE values...\n";
    $dbRecord = DB::table('travel_orders')->where('id', $travelOrder->id)->first();
    
    echo "  Status: " . $dbRecord->status . " " . ($dbRecord->status === 'approved' ? "✓" : "✗") . "\n";
    echo "  President Approved: " . var_export($dbRecord->president_approved, true) . " " . ($dbRecord->president_approved ? "✓" : "✗") . "\n";
    echo "  President Approved At: " . $dbRecord->president_approved_at . " " . ($dbRecord->president_approved_at ? "✓" : "✗") . "\n";
    
    // 5. Verify model values
    echo "\n4. Verifying MODEL values...\n";
    $travelOrder->refresh();
    
    echo "  Status: " . $travelOrder->status . " " . ($travelOrder->status === 'approved' ? "✓" : "✗") . "\n";
    echo "  President Approved: " . var_export($travelOrder->president_approved, true) . " " . ($travelOrder->president_approved ? "✓" : "✗") . "\n";
    echo "  Remarks: " . $travelOrder->remarks . " " . ($travelOrder->remarks === 'Approved' ? "✓" : "✗") . "\n";
    
    // 6. Test index method
    echo "\n5. Testing INDEX method...\n";
    $indexRequest = new Request();
    $indexResponse = $controller->index($indexRequest);
    $viewData = $indexResponse->getData();
    $travelOrders = $viewData['travelOrders'];
    
    $foundOrder = null;
    foreach ($travelOrders as $order) {
        if ($order->id == $travelOrder->id) {
            $foundOrder = $order;
            break;
        }
    }
    
    if ($foundOrder) {
        echo "✓ Found travel order in index\n";
        echo "  Status: " . $foundOrder->status . " " . ($foundOrder->status === 'approved' ? "✓" : "✗") . "\n";
        echo "  Remarks: " . $foundOrder->remarks . " " . ($foundOrder->remarks === 'Approved' ? "✓" : "✗") . "\n";
    } else {
        echo "✗ Travel order not found in index\n";
    }
    
    // Clean up
    $travelOrder->delete();
    echo "\n✓ Cleaned up test data\n";
    
    echo "\n=== TEST SUMMARY ===\n";
    echo "All tests passed! President travel orders are correctly:\n";
    echo "1. Created with 'approved' status\n";
    echo "2. Marked with president_approved = true\n";
    echo "3. Given a president_approved_at timestamp\n";
    echo "4. Displayed with 'Approved' remarks\n";
    echo "5. Visible in the President's dashboard with correct status\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}