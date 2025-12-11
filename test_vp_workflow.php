<?php
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TravelOrder;
use App\Models\Employee;
use App\Http\Controllers\VpTravelOrderController;
use Illuminate\Http\Request;

// Test the complete workflow as described
try {
    echo "=== Testing VP Dashboard Workflow ===\n\n";
    
    // Get the VP employee (Ravelina Velasco - ID: 9)
    $vpEmployee = Employee::find(9);
    
    if (!$vpEmployee) {
        echo "VP not found\n";
        exit;
    }
    
    echo "VP: " . $vpEmployee->first_name . " " . $vpEmployee->last_name . " (ID: " . $vpEmployee->id . ", Office ID: " . $vpEmployee->office_id . ")\n";
    
    // Mock the Auth facade
    \Illuminate\Support\Facades\Auth::shouldReceive('user')->andReturn((object)[
        'employee' => $vpEmployee
    ]);
    
    // Create controller instance
    $controller = new VpTravelOrderController();
    
    // 1. Test Pending Tab - Should show head travel orders approved by division head
    echo "\n1. Testing Pending Tab:\n";
    $pendingRequest = new Request();
    $pendingRequest->merge(['tab' => 'pending']);
    
    $pendingResponse = $controller->index($pendingRequest);
    $pendingTravelOrders = $pendingResponse->getData()['travelOrders'];
    
    echo "Found " . count($pendingTravelOrders) . " travel orders in Pending tab:\n";
    
    $foundHeadRequest = false;
    foreach ($pendingTravelOrders as $travelOrder) {
        if ($travelOrder->employee->is_head) {
            echo "  ✓ Head travel order ID: " . $travelOrder->id . " (Employee: " . $travelOrder->employee->first_name . " " . $travelOrder->employee->last_name . ")\n";
            echo "    Division Head Approved: " . var_export($travelOrder->divisionhead_approved, true) . "\n";
            echo "    VP Approved: " . var_export($travelOrder->vp_approved, true) . "\n";
            echo "    Remarks: " . $travelOrder->remarks . "\n";
            $foundHeadRequest = true;
        }
    }
    
    if (!$foundHeadRequest) {
        echo "  ✗ No head travel orders found in Pending tab\n";
    }
    
    // 2. Approve a head travel order
    echo "\n2. Approving a head travel order:\n";
    
    // Find a head travel order that's pending VP approval
    $headTravelOrderToApprove = null;
    foreach ($pendingTravelOrders as $travelOrder) {
        if ($travelOrder->employee->is_head && is_null($travelOrder->vp_approved) && $travelOrder->divisionhead_approved) {
            $headTravelOrderToApprove = $travelOrder;
            break;
        }
    }
    
    if ($headTravelOrderToApprove) {
        echo "  Approving travel order ID: " . $headTravelOrderToApprove->id . "\n";
        
        // Approve the travel order
        $approveResponse = $controller->approve($headTravelOrderToApprove);
        
        // Refresh the travel order
        $headTravelOrderToApprove->refresh();
        
        echo "  After approval:\n";
        echo "    VP Approved: " . var_export($headTravelOrderToApprove->vp_approved, true) . "\n";
        echo "    Status: " . $headTravelOrderToApprove->status . "\n";
        echo "    Remarks: " . $headTravelOrderToApprove->remarks . "\n";
    } else {
        echo "  No eligible head travel order found to approve\n";
    }
    
    // 3. Test Approved Tab - Should show the approved head travel order
    echo "\n3. Testing Approved Tab:\n";
    $approvedRequest = new Request();
    $approvedRequest->merge(['tab' => 'approved']);
    
    $approvedResponse = $controller->index($approvedRequest);
    $approvedTravelOrders = $approvedResponse->getData()['travelOrders'];
    
    echo "Found " . count($approvedTravelOrders) . " travel orders in Approved tab:\n";
    
    $foundApprovedHeadRequest = false;
    foreach ($approvedTravelOrders as $travelOrder) {
        if ($travelOrder->employee->is_head && $travelOrder->vp_approved) {
            echo "  ✓ Approved head travel order ID: " . $travelOrder->id . " (Employee: " . $travelOrder->employee->first_name . " " . $travelOrder->employee->last_name . ")\n";
            echo "    Division Head Approved: " . var_export($travelOrder->divisionhead_approved, true) . "\n";
            echo "    VP Approved: " . var_export($travelOrder->vp_approved, true) . "\n";
            echo "    Status: " . $travelOrder->status . "\n";
            echo "    Remarks: " . $travelOrder->remarks . "\n";
            $foundApprovedHeadRequest = true;
        }
    }
    
    if (!$foundApprovedHeadRequest) {
        echo "  ✗ No approved head travel orders found in Approved tab\n";
    }
    
    echo "\n=== Workflow Test Completed ===\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}