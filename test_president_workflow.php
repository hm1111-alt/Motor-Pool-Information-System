<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use App\Http\Controllers\PresidentTravelOrderController;
use Illuminate\Http\Request;

// Test the president workflow
try {
    echo "=== Testing President Workflow ===\n\n";
    
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
    $controller = new PresidentTravelOrderController();
    
    // Test Pending Tab - Should show division head travel orders approved by VP
    echo "\n1. Testing Pending Tab for Division Head Travel Orders (approved by VP):\n";
    $pendingRequest = new Request();
    $pendingRequest->merge(['tab' => 'pending']);
    
    $pendingResponse = $controller->index($pendingRequest);
    $pendingTravelOrders = $pendingResponse->getData()['travelOrders'];
    
    echo "Found " . count($pendingTravelOrders) . " travel orders in Pending tab:\n";
    
    $foundDivisionHeadRequest = false;
    foreach ($pendingTravelOrders as $travelOrder) {
        if ($travelOrder->employee->is_divisionhead) {
            echo "  ✓ Division Head travel order ID: " . $travelOrder->id . " (Employee: " . $travelOrder->employee->first_name . " " . $travelOrder->employee->last_name . ")\n";
            echo "    VP Approved: " . var_export($travelOrder->vp_approved, true) . "\n";
            echo "    President Approved: " . var_export($travelOrder->president_approved, true) . "\n";
            echo "    Remarks: " . $travelOrder->remarks . "\n";
            $foundDivisionHeadRequest = true;
        }
    }
    
    if (!$foundDivisionHeadRequest) {
        echo "  ✗ No division head travel orders found in Pending tab\n";
    }
    
    // Find a division head travel order that's pending president approval
    $divisionHeadTravelOrderToApprove = null;
    foreach ($pendingTravelOrders as $travelOrder) {
        if ($travelOrder->employee->is_divisionhead && $travelOrder->vp_approved && is_null($travelOrder->president_approved)) {
            $divisionHeadTravelOrderToApprove = $travelOrder;
            break;
        }
    }
    
    if ($divisionHeadTravelOrderToApprove) {
        echo "\n2. Approving division head travel order ID: " . $divisionHeadTravelOrderToApprove->id . "\n";
        
        // Approve the travel order
        $approveResponse = $controller->approve($divisionHeadTravelOrderToApprove);
        
        // Refresh the travel order
        $divisionHeadTravelOrderToApprove->refresh();
        
        echo "  After approval:\n";
        echo "    President Approved: " . var_export($divisionHeadTravelOrderToApprove->president_approved, true) . "\n";
        echo "    Status: " . $divisionHeadTravelOrderToApprove->status . "\n";
        echo "    Remarks: " . $divisionHeadTravelOrderToApprove->remarks . "\n";
    } else {
        echo "\n2. No eligible division head travel order found to approve\n";
    }
    
    // Test Approved Tab - Should show approved division head travel orders
    echo "\n3. Testing Approved Tab for Division Head Travel Orders:\n";
    $approvedRequest = new Request();
    $approvedRequest->merge(['tab' => 'approved']);
    
    $approvedResponse = $controller->index($approvedRequest);
    $approvedTravelOrders = $approvedResponse->getData()['travelOrders'];
    
    echo "Found " . count($approvedTravelOrders) . " travel orders in Approved tab:\n";
    
    $foundApprovedDivisionHeadRequest = false;
    foreach ($approvedTravelOrders as $travelOrder) {
        if ($travelOrder->employee->is_divisionhead && $travelOrder->president_approved) {
            echo "  ✓ Approved division head travel order ID: " . $travelOrder->id . " (Employee: " . $travelOrder->employee->first_name . " " . $travelOrder->employee->last_name . ")\n";
            echo "    President Approved: " . var_export($travelOrder->president_approved, true) . "\n";
            echo "    Status: " . $travelOrder->status . "\n";
            echo "    Remarks: " . $travelOrder->remarks . "\n";
            $foundApprovedDivisionHeadRequest = true;
        }
    }
    
    if (!$foundApprovedDivisionHeadRequest) {
        echo "  ✗ No approved division head travel orders found in Approved tab\n";
    }
    
    echo "\n=== President Workflow Test Completed ===\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}