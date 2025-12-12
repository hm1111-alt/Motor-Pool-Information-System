<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use App\Http\Controllers\PresidentOwnTravelOrderController;
use Illuminate\Http\Request;

// Test the President self-service workflow
try {
    echo "=== Testing President Self-Service Workflow ===\n\n";
    
    // Get a president employee
    $presidentEmployee = Employee::where('is_president', 1)->first();
    
    if (!$presidentEmployee) {
        echo "No president found\n";
        exit;
    }
    
    echo "President: " . $presidentEmployee->first_name . " " . $presidentEmployee->last_name . " (ID: " . $presidentEmployee->id . ", Office ID: " . $presidentEmployee->office_id . ")\n";
    
    // Mock the Auth facade
    \Illuminate\Support\Facades\Auth::shouldReceive('user')->andReturn((object)[
        'employee' => $presidentEmployee
    ]);
    
    // Create controller instance
    $controller = new PresidentOwnTravelOrderController();
    
    // Test Pending Tab - Should show president's travel orders
    echo "\n1. Testing Pending Tab for President's Travel Orders:\n";
    $pendingRequest = new Request();
    $pendingRequest->merge(['tab' => 'pending']);
    
    $pendingResponse = $controller->index($pendingRequest);
    $pendingTravelOrders = $pendingResponse->getData()['travelOrders'];
    
    echo "Found " . count($pendingTravelOrders) . " travel orders in Pending tab:\n";
    
    foreach ($pendingTravelOrders as $travelOrder) {
        echo "  ✓ President travel order ID: " . $travelOrder->id . " (Status: " . $travelOrder->status . ", Remarks: " . $travelOrder->remarks . ")\n";
    }
    
    // Test Approved Tab - Should show approved president's travel orders
    echo "\n2. Testing Approved Tab for President's Travel Orders:\n";
    $approvedRequest = new Request();
    $approvedRequest->merge(['tab' => 'approved']);
    
    $approvedResponse = $controller->index($approvedRequest);
    $approvedTravelOrders = $approvedResponse->getData()['travelOrders'];
    
    echo "Found " . count($approvedTravelOrders) . " travel orders in Approved tab:\n";
    
    foreach ($approvedTravelOrders as $travelOrder) {
        echo "  ✓ Approved president travel order ID: " . $travelOrder->id . " (Status: " . $travelOrder->status . ", Remarks: " . $travelOrder->remarks . ")\n";
    }
    
    echo "\n=== President Self-Service Workflow Test Completed ===\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}