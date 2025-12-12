<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use App\Http\Controllers\PresidentTravelOrderController;
use Illuminate\Http\Request;

// Test the VP to President workflow
try {
    echo "=== Testing VP to President Workflow ===\n\n";
    
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
    
    // Test Pending Tab - Should show VP travel orders
    echo "\n1. Testing Pending Tab for VP Travel Orders:\n";
    $pendingRequest = new Request();
    $pendingRequest->merge(['tab' => 'pending']);
    
    $pendingResponse = $controller->index($pendingRequest);
    $pendingTravelOrders = $pendingResponse->getData()['travelOrders'];
    
    echo "Found " . count($pendingTravelOrders) . " travel orders in Pending tab:\n";
    
    $foundVPRequest = false;
    foreach ($pendingTravelOrders as $travelOrder) {
        if ($travelOrder->employee->is_vp) {
            echo "  ✓ VP travel order ID: " . $travelOrder->id . " (Employee: " . $travelOrder->employee->first_name . " " . $travelOrder->employee->last_name . ")\n";
            echo "    VP Approved: " . var_export($travelOrder->vp_approved, true) . "\n";
            echo "    President Approved: " . var_export($travelOrder->president_approved, true) . "\n";
            echo "    Remarks: " . $travelOrder->remarks . "\n";
            $foundVPRequest = true;
        }
    }
    
    if (!$foundVPRequest) {
        echo "  ✗ No VP travel orders found in Pending tab\n";
    }
    
    // Find a VP travel order that's pending president approval
    $vpTravelOrderToApprove = null;
    foreach ($pendingTravelOrders as $travelOrder) {
        if ($travelOrder->employee->is_vp && is_null($travelOrder->president_approved)) {
            $vpTravelOrderToApprove = $travelOrder;
            break;
        }
    }
    
    if ($vpTravelOrderToApprove) {
        echo "\n2. Approving VP travel order ID: " . $vpTravelOrderToApprove->id . "\n";
        
        // Approve the travel order
        $approveResponse = $controller->approve($vpTravelOrderToApprove);
        
        // Refresh the travel order
        $vpTravelOrderToApprove->refresh();
        
        echo "  After approval:\n";
        echo "    President Approved: " . var_export($vpTravelOrderToApprove->president_approved, true) . "\n";
        echo "    Status: " . $vpTravelOrderToApprove->status . "\n";
        echo "    Remarks: " . $vpTravelOrderToApprove->remarks . "\n";
    } else {
        echo "\n2. No eligible VP travel order found to approve\n";
    }
    
    // Test Approved Tab - Should show approved VP travel orders
    echo "\n3. Testing Approved Tab for VP Travel Orders:\n";
    $approvedRequest = new Request();
    $approvedRequest->merge(['tab' => 'approved']);
    
    $approvedResponse = $controller->index($approvedRequest);
    $approvedTravelOrders = $approvedResponse->getData()['travelOrders'];
    
    echo "Found " . count($approvedTravelOrders) . " travel orders in Approved tab:\n";
    
    $foundApprovedVPRequest = false;
    foreach ($approvedTravelOrders as $travelOrder) {
        if ($travelOrder->employee->is_vp && $travelOrder->president_approved) {
            echo "  ✓ Approved VP travel order ID: " . $travelOrder->id . " (Employee: " . $travelOrder->employee->first_name . " " . $travelOrder->employee->last_name . ")\n";
            echo "    President Approved: " . var_export($travelOrder->president_approved, true) . "\n";
            echo "    Status: " . $travelOrder->status . "\n";
            echo "    Remarks: " . $travelOrder->remarks . "\n";
            $foundApprovedVPRequest = true;
        }
    }
    
    if (!$foundApprovedVPRequest) {
        echo "  ✗ No approved VP travel orders found in Approved tab\n";
    }
    
    echo "\n=== VP to President Workflow Test Completed ===\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}